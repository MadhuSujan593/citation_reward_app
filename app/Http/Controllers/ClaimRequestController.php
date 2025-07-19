<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use App\Models\PublishedPaper;
use App\Services\AdminWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClaimRequestController extends Controller
{
    /**
     * Show claim request form for citers
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get papers with pending or approved claims by user (exclude rejected claims)
        $claimedPaperIds = $user->claimRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('referenced_paper_id')
            ->toArray();
        
        // Get user's cited papers excluding those with pending/approved claims
        // (Allow papers with rejected claims to be claimed again)
        $citedPapers = $user->citedPapers()
            ->with('user')
            ->whereNotIn('published_papers.id', $claimedPaperIds)
            ->get();
        
        // Get user's claim requests
        $claimRequests = $user->claimRequests()
            ->with(['referencedPaper', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $userRole = $user->role ?? 'Citer';

        return view('claim-requests.index', compact('citedPapers', 'claimRequests', 'userRole'));
    }

    /**
     * Store a new claim request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'citer_paper_title' => 'required|string|max:255',
            'paper_link' => 'required|url|max:1000',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'referenced_paper_id' => 'required|exists:published_papers,id',
            'reference_id' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Check if user has actually cited this paper
            $hasCited = $user->citedPapers()->where('published_papers.id', $request->referenced_paper_id)->exists();
            if (!$hasCited) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only claim for papers you have cited.'
                ], 400);
            }

            // Check if pending or approved claim already exists for this paper
            $existingActiveClaim = ClaimRequest::where('user_id', $user->id)
                ->where('referenced_paper_id', $request->referenced_paper_id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();
                
            if ($existingActiveClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending or approved claim for this paper. Please wait for the current claim to be processed.'
                ], 400);
            }

            $pdfPath = null;
            if ($request->hasFile('pdf_document')) {
                $pdfPath = $request->file('pdf_document')->store('claim-documents', 'public');
            }

            ClaimRequest::create([
                'user_id' => $user->id,
                'citer_paper_title' => $request->citer_paper_title,
                'paper_link' => $request->paper_link,
                'pdf_document' => $pdfPath,
                'referenced_paper_id' => $request->referenced_paper_id,
                'reference_id' => $request->reference_id,
                'claim_amount' => 95.00 // 100 - 5% commission = 95
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Claim request submitted successfully! Admin will review it soon.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit claim request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin dashboard - show all claim requests
     */
    public function adminIndex()
    {
        $user = Auth::user();
        
        // Check if user is admin
        if ($user->email !== 'admin@citationapp.com') {
            abort(403, 'Access denied. Only admins can access this page.');
        }

        $claimRequests = ClaimRequest::with(['user', 'referencedPaper.user', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $userRole = $user->role ?? 'Admin';

        return view('admin.claim-requests', compact('claimRequests', 'userRole'));
    }

    /**
     * Admin - approve claim request
     */
    public function approve(Request $request, ClaimRequest $claimRequest)
    {
        $user = Auth::user();
        
        if ($user->email !== 'admin@citationapp.com') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$claimRequest->canBeApproved()) {
            return response()->json(['success' => false, 'message' => 'This claim cannot be approved'], 400);
        }

        try {
            DB::beginTransaction();

            // Get admin wallet
            $adminWallet = AdminWalletService::getAdminWallet();
            
            // Fixed amounts: ₹95 to citer, ₹5 commission for admin
            $payoutAmount = 95.00;
            
            // Check if admin has sufficient funds
            if ($adminWallet->balance < $payoutAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient admin funds to process this claim'
                ], 400);
            }

            // Get citer's wallet
            $citerWallet = $claimRequest->user->wallet;
            if (!$citerWallet) {
                $citerWallet = \App\Models\Wallet::create([
                    'user_id' => $claimRequest->user->id,
                    'balance' => 0.00,
                    'currency' => 'INR',
                    'is_active' => true
                ]);
            }

            // Transfer ₹95 from admin to citer (₹5 remains as commission)
            $adminWallet->deductFunds(
                $payoutAmount,
                'Claim payment for: ' . substr($claimRequest->citer_paper_title, 0, 50) . '...',
                $claimRequest->id,
                'claim_payment'
            );

            $citerWallet->addFunds(
                $payoutAmount,
                'Claim payment received for: ' . substr($claimRequest->citer_paper_title, 0, 50) . '...',
                $claimRequest->id,
                'claim_payment'
            );

            // Update claim request
            $claimRequest->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes ?? 'Claim approved and payment processed',
                'reviewed_at' => now(),
                'reviewed_by' => $user->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Claim approved! ₹95.00 transferred to citer, ₹5.00 commission retained.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve claim: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin - reject claim request
     */
    public function reject(Request $request, ClaimRequest $claimRequest)
    {
        $user = Auth::user();
        
        if ($user->email !== 'admin@citationapp.com') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$claimRequest->canBeRejected()) {
            return response()->json(['success' => false, 'message' => 'This claim cannot be rejected'], 400);
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide rejection reason',
                'errors' => $validator->errors()
            ], 422);
        }

        $claimRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim rejected successfully.'
        ]);
    }
}
