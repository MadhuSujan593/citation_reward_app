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
                'author_id' => 'required',
                'title' => 'required|string',
                'mla' => 'nullable|string',
                'apa' => 'nullable|string',
                'chicago' => 'nullable|string',
                'harvard' => 'nullable|string',
                'vancouver' => 'nullable|string',
            ]);

            PublishedPaper::create([
                'user_id' => Auth::id(),
                'author_id' => $validated['author_id'],
                'title' => $validated['title'],
                'mla' => $validated['mla'],
                'apa' => $validated['apa'],
                'chicago' => $validated['chicago'],
                'harvard' => $validated['harvard'],
                'vancouver' => $validated['vancouver'],
            ]);

            return response()->json(['success' => true, ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to Upload the papers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
