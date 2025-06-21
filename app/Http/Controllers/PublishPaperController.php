<?php

namespace App\Http\Controllers;

use App\Models\PublishedPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublishPaperController extends Controller
{
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
                'doi' => 'nullable|string',
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
            'doi' => 'nullable|string',
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

    public function cite(PublishedPaper $publishedPaper)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Check if already cited
            if ($publishedPaper->citers()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'You have already cited this paper.'], 409);
            }

            // Attach the user as a citer
            $publishedPaper->citers()->attach($user->id);

            return response()->json(['success' => true,]);
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

    public function unCite(PublishedPaper $publishedPaper)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }

            // Check if the user has cited the paper
            if (! $publishedPaper->citers()->where('user_id', $user->id)->exists()) {
                return response()->json(['message' => 'You have not cited this paper.'], 409);
            }

            // Detach the user as a citer
            $publishedPaper->citers()->detach($user->id);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Uncitation error: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'paper_id' => $publishedPaper->id,
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred while removing the citation.'
            ], 500);
        }
    }

}
