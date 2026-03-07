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
        // Actually, we want to allow multiple claims if they select DIFFERENT citations.
        // But for now, let's keep it simple: if a paper has an active claim, we might hide it or show available citations.
        // The user's request says "Select your cited papers", implying we select from available citations.
        
        $citedPapers = \App\Models\PublishedPaper::whereHas('citers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with('user')
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
     * Get citations for a specific paper for the current user
     */
    public function getCitations(PublishedPaper $paper)
    {
        $user = Auth::user();
        
        // Get all citations by this user for this paper
        $citations = \App\Models\PaperCitation::where('user_id', $user->id)
            ->where('published_paper_id', $paper->id)
            ->get();
            
        // Get IDs of citations already in pending/approved claims
        $claimedCitationIds = [];
        $existingClaims = ClaimRequest::where('user_id', $user->id)
            ->where('referenced_paper_id', $paper->id)
            ->whereIn('status', ['pending', 'approved'])
            ->get();
            
        foreach ($existingClaims as $claim) {
            if ($claim->selected_citations) {
                foreach ($claim->selected_citations as $cit) {
                    $claimedCitationIds[] = $cit['id'];
                }
            }
        }
        
        // Mark citations as already claimed
        foreach ($citations as $citation) {
            $citation->already_claimed = in_array($citation->id, $claimedCitationIds);
        }

        return response()->json([
            'success' => true,
            'citations' => $citations
        ]);
    }

    /**
     * Store a new claim request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'citer_paper_title' => 'nullable|string|max:255',
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

            $selectedCitations = $request->input('selected_citations', []);
            $claimAmount = $request->input('claim_amount', 100);

            // Get IDs of citations already in pending/approved claims for this user and paper
            $claimedCitationIds = [];
            $existingClaims = ClaimRequest::where('user_id', $user->id)
                ->where('referenced_paper_id', $request->referenced_paper_id)
                ->whereIn('status', ['pending', 'approved'])
                ->get();
                
            foreach ($existingClaims as $claim) {
                if ($claim->selected_citations) {
                    foreach ($claim->selected_citations as $cit) {
                        if (isset($cit['id'])) {
                            $claimedCitationIds[] = (int)$cit['id'];
                        }
                    }
                }
            }

            // Verify none of the newly selected citations are already claimed
            foreach ($selectedCitations as $citData) {
                if (isset($citData['id']) && in_array((int)$citData['id'], $claimedCitationIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'One or more of the selected citations have already been claimed.'
                    ], 400);
                }
            }

            $pdfPath = null;
            if ($request->hasFile('pdf_document')) {
                $pdfPath = $request->file('pdf_document')->store('claim-documents', 'public');
            }

            // Update citation titles if they were changed
            foreach ($selectedCitations as $citData) {
                if (isset($citData['id']) && isset($citData['title'])) {
                    \App\Models\PaperCitation::where('id', $citData['id'])
                        ->where('user_id', $user->id)
                        ->update(['citing_paper_title' => $citData['title']]);
                }
            }

            $claim = ClaimRequest::create([
                'user_id' => $user->id,
                'citer_paper_title' => $request->citer_paper_title,
                'paper_link' => $request->paper_link,
                'pdf_document' => $pdfPath,
                'referenced_paper_id' => $request->referenced_paper_id,
                'reference_id' => $request->reference_id,
                'claim_amount' => $claimAmount,
                'selected_citations' => $selectedCitations
            ]);

            DB::commit();

            // Notify Paper Funder about the citation claim
            try {
                $referencedPaper = PublishedPaper::find($request->referenced_paper_id);
                $funder = $referencedPaper->user;
                if ($funder) {
                    $funder->notify(new \App\Notifications\PaperCitedNotification($referencedPaper, $user, $claimAmount));
                    \Illuminate\Support\Facades\Log::info('Citation notification sent to funder on claim submission: ' . $funder->email);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send citation notification on claim submission: ' . $e->getMessage());
            }

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
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is admin
        if ($user->role !== 'Admin') {
            abort(403, 'Access denied. Only admins can access this page.');
        }

        $query = ClaimRequest::with(['user', 'referencedPaper.user', 'reviewedBy']);

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $claimRequests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $userRole = $user->role ?? 'Admin';

        return view('admin.claim-requests', compact('claimRequests', 'userRole'));
    }

    /**
     * Admin - approve claim request
     */
    public function approve(Request $request, ClaimRequest $claimRequest)
    {
        $user = Auth::user();
        
        if ($user->role !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!$claimRequest->canBeApproved()) {
            return response()->json(['success' => false, 'message' => 'This claim cannot be approved'], 400);
        }

        try {
            DB::beginTransaction();

            // Get admin wallet
            $adminWallet = AdminWalletService::getAdminWallet();
            
            // Dynamic amount: User specified amount - 5% commission
            $totalAmount = $claimRequest->claim_amount;
            $commission = $totalAmount * 0.05;
            $payoutAmount = $totalAmount - $commission;
            
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
                    'balance' => 0,
                    'currency' => 'INR',
                    'is_active' => true
                ]);
            }

            // Transfer payout from admin to citer
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
                'message' => 'Claim approved! ₹' . number_format($payoutAmount, 2) . ' transferred to citer, ₹' . number_format($commission, 2) . ' commission retained.'
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
        
        if ($user->role !== 'Admin') {
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
