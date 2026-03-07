<?php

namespace App\Http\Controllers;

use App\Models\PaperCitation;
use App\Models\PublishedPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublishPaperController extends Controller
{
    public function create()
    {
        $userRole = auth()->user()->role ?? 'Citer';
        return view('papers.create', compact('userRole'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'mla' => 'nullable|string',
                'apa' => 'nullable|string',
                'chicago' => 'nullable|string',
                'harvard' => 'nullable|string',
                'vancouver' => 'nullable|string',
                'doi' => 'nullable|string|unique:published_papers,doi',
            ]);

            PublishedPaper::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'mla' => $validated['mla'],
                'apa' => $validated['apa'],
                'chicago' => $validated['chicago'],
                'harvard' => $validated['harvard'],
                'vancouver' => $validated['vancouver'],
                'doi' => $validated['doi'],
            ]);

            return response()->json(['success' => true,]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to Upload the papers: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, PublishedPaper $paper)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'mla' => 'nullable|string',
            'apa' => 'nullable|string',
            'chicago' => 'nullable|string',
            'harvard' => 'nullable|string',
            'vancouver' => 'nullable|string',
            'doi' => 'nullable|string|unique:published_papers,doi,' . $paper->id,
        ]);

        $paper->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paper updated successfully.',
            'paper' => $paper
        ]);
    }

    public function destroy(PublishedPaper $paper)
    {
        $paper->delete();

        return response()->json([
            'success' => true,
            'message' => 'Paper deleted successfully.'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = preg_replace('/^\d+/', '', $request->query('role'));

        $filterType = $request->input('filter_type');

        $papers = PublishedPaper::with('user');
        $userId = auth()->id();
        // Apply role-based condition
        if ($role === 'Citer') {
            $papers->where('user_id', '!=', $userId);
        } elseif ($role === 'Funder') {
            $papers->where('user_id', $userId);
        }
        if ($query) {
            $papers->where(function ($q) use ($query, $filterType) {
                switch ($filterType) {
                    case 'author_id':
                        // Filter by actual user_id
                        $q->where('user_id', 'like', "%$query%");
                        break;

                    case 'author_name':
                        // Join with user and filter on first_name + last_name
                        $q->whereHas('user', function ($uq) use ($query) {
                            $uq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$query%"]);
                        });
                        break;

                    case 'title_name':
                        $q->where('title', 'like', "%$query%");
                        break;

                    default:
                        $q->where(function ($subQuery) use ($query) {
                            $subQuery->where('title', 'like', "%$query%")
                                ->orWhere('user_id', 'like', "%$query%")
                                ->orWhereHas('user', function ($uq) use ($query) {
                                    $uq->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$query%"]);
                                })
                                ->orWhere('mla', 'like', "%$query%")
                                ->orWhere('apa', 'like', "%$query%")
                                ->orWhere('chicago', 'like', "%$query%")
                                ->orWhere('harvard', 'like', "%$query%")
                                ->orWhere('vancouver', 'like', "%$query%")
                                ->orWhere('doi', 'like', "%$query%");
                        });
                        break;
                }
            });
        }

        return response()->json($papers->latest()->get());
    }

    public function cite(Request $request, PublishedPaper $publishedPaper)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            $validated = $request->validate([
                'citing_paper_title' => 'required|string|max:255',
            ]);

            // Process payment for citation (deduct from paper funder)
            $paymentResult = \App\Services\CitationPaymentService::processCitationPayment($user->id, $publishedPaper->id);
            
            if (!$paymentResult['success']) {
                return response()->json(['message' => $paymentResult['message']], 400);
            }

            // Attach the user as a citer with the paper title
            $publishedPaper->citers()->attach($user->id, [
                'citing_paper_title' => $validated['citing_paper_title']
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Citation error: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'paper_id' => $publishedPaper->id,
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred while citing the paper.'
            ], 500);
        }
    }

    public function unCite(PaperCitation $paperCitation)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Check if user owns this citation
            if ($paperCitation->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized citation removal.'], 403);
            }

            // Check if user has submitted a claim for this paper
            $existingClaim = \App\Models\ClaimRequest::where('user_id', $user->id)
                ->where('referenced_paper_id', $paperCitation->published_paper_id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();
                
            if ($existingClaim) {
                return response()->json(['message' => 'Cannot uncite - you have a pending or approved claim for this paper.'], 400);
            }

            // Process refund for uncitation (refund to paper funder)
            $refundResult = \App\Services\CitationPaymentService::processCitationRefund($user->id, $paperCitation->published_paper_id);
            
            if (!$refundResult['success']) {
                return response()->json(['message' => $refundResult['message']], 400);
            }

            // Delete the citation
            $paperCitation->delete();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Uncitation error: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'citation_id' => $paperCitation->id,
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred while removing the citation.'
            ], 500);
        }
    }

    public function myCitations(Request $request)
    {
        $userId = auth()->id();
        $page = $request->input('page', 1);

        // Get unique papers cited by the user
        $citedPaperIds = PaperCitation::where('user_id', $userId)
            ->distinct()
            ->pluck('published_paper_id');

        $papersQuery = PublishedPaper::whereIn('id', $citedPaperIds)
            ->with(['user']);

        $paginatedPapers = $papersQuery->paginate(6);

        // For each paper, attach all citations by this user, including claim status
        $papers = collect($paginatedPapers->items())->map(function($paper) use ($userId) {
            $userCitations = PaperCitation::where('user_id', $userId)
                ->where('published_paper_id', $paper->id)
                ->latest()
                ->get(['id', 'citing_paper_title']);

            // Get all citation IDs for this paper that are already in a pending/approved claim
            $claimedCitationIds = [];
            $existingClaims = \App\Models\ClaimRequest::where('user_id', $userId)
                ->where('referenced_paper_id', $paper->id)
                ->whereIn('status', ['pending', 'approved'])
                ->get();
                
            foreach ($existingClaims as $claim) {
                if (is_array($claim->selected_citations)) {
                    foreach ($claim->selected_citations as $cit) {
                        if (isset($cit['id'])) {
                            $claimedCitationIds[] = (int)$cit['id'];
                        }
                    }
                }
            }

            foreach ($userCitations as $citation) {
                $citation->already_claimed = in_array($citation->id, $claimedCitationIds);
            }

            $paper->setAttribute('is_paper_cited_by_current_user', true);
            $paper->setAttribute('all_citations', $userCitations);
            
            // For backward compatibility or single display if needed
            $paper->setAttribute('citing_paper_title', $userCitations->first()->citing_paper_title ?? '');
            $paper->setAttribute('citation_id', $userCitations->first()->id ?? null);
            
            return $paper;
        });

        return response()->json([
            'success' => true,
            'papers' => $papers,
            'pagination' => [
                'current_page' => $paginatedPapers->currentPage(),
                'last_page' => $paginatedPapers->lastPage(),
                'total' => $paginatedPapers->total(),
            ],
            'stats' => [
                'totalPapers' => PublishedPaper::where('user_id', '!=', $userId)->count(),
                'totalCitations' => PaperCitation::where('user_id', $userId)->count(),
            ]
        ]);
    }

}
