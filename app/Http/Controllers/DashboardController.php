<?php

namespace App\Http\Controllers;

use App\Models\PaperCitation;
use App\Models\PublishedPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fallback to 'Citer' if null
        $userRole = $user->role ?? 'Citer';

        return view('dashboard-new', compact('userRole'));
    }

    public function switchView($type)
    {
        if (!in_array($type, ['citer', 'funder'])) {
            abort(404);
        }

        session(['view_type' => $type]);

        return redirect()->route('dashboard');
    }

    public function showPapers(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        if ($role === 'Funder') {
            $papers = PublishedPaper::where('user_id', $user->id)
            ->latest()->get();
            $totalPapers = $papers->count();
            $totalCitations = PaperCitation::whereIn('published_paper_id', $papers->pluck('id'))->count();
        } elseif ($role === 'Citer') {
            $papers = PublishedPaper::where('user_id', '!=', $user->id)->latest()->get();
            $totalPapers = $papers->count();
            $totalCitations = PaperCitation::where('user_id', $user->id)->count();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'papers' => $papers,
            'stats' => [
                'totalPapers' => $totalPapers,
                'totalCitations' => $totalCitations,
                'recentActivity' => 0,
            ]
        ]);
    }

    public function setRole(Request $request)
    {
        $user = Auth::user();
        $role = $request->input('role');

        $validRoles = ['Citer', 'Funder', 'Admin'];

        if (!in_array($role, $validRoles)) {
            return response()->json(['success' => false, 'message' => 'Invalid role.'], 400);
        }

        $user->role = $role;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Dashboard role updated.']);
    }

}
