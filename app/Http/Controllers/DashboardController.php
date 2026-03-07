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
        $user = auth()->user()->fresh();
        
        // Ensure the global auth instance mirrors the fresh user data
        auth()->setUser($user);

        // Normalize the role just in case it's fully capitalized in the database (e.g. 'ADMIN')
        $userRole = ucfirst(strtolower(trim($user->role ?? 'Citer')));

        // Redirect super admin to their management panel
        if ($userRole === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($userRole === 'Funder') {
            return redirect()->route('funder.dashboard');
        }

        return redirect()->route('citer.dashboard');
    }

    public function citerIndex() {
        $userRole = 'Citer';
        return view('citer-dashboard', compact('userRole'));
    }

    public function funderIndex() {
        $userRole = 'Funder';
        return view('funder-dashboard', compact('userRole'));
    }

    public function adminIndex() {
        $userRole = 'Admin';
        return view('admin-dashboard', compact('userRole'));
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
        $rawRole = $request->input('role') ?? $user->role;
        $role = ucfirst(strtolower(trim($rawRole)));

        $query = PublishedPaper::with(['user']);

        if ($role === 'Admin') {
            // Admin sees all papers
            $papers = $query->withCount('citers')->latest()->paginate(6);
            $totalPapers = PublishedPaper::count();
            $totalCitations = PaperCitation::count();
            
        } elseif ($role === 'Funder') {
            $query->where('user_id', $user->id);
            
            $papers = $query->withCount('citers')->latest()->paginate(6);
            
            $totalPapers = PublishedPaper::where('user_id', $user->id)->count();
            $totalCitations = PaperCitation::whereIn('published_paper_id', function($q) use ($user) {
                $q->select('id')->from('published_papers')->where('user_id', $user->id);
            })->count();

        } elseif ($role === 'Citer') {
            $query->where('user_id', '!=', $user->id)
                  ->whereHas('user', function($q) {
                      $q->where('status', 'active');
                  });
            
            $papers = $query->latest()->paginate(6);
            
            $totalPapers = PublishedPaper::where('user_id', '!=', $user->id)
                ->whereHas('user', function($q) {
                    $q->where('status', 'active');
                })->count();
            $totalCitations = PaperCitation::where('user_id', $user->id)->count();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'papers' => $papers->items(),
            'pagination' => [
                'current_page' => $papers->currentPage(),
                'last_page' => $papers->lastPage(),
                'total' => $papers->total(),
            ],
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

        Log::info("Attempting to switch role for user {$user->id} to {$role}");

        $validRoles = ['Citer', 'Funder', 'Admin'];

        if (!in_array($role, $validRoles)) {
            Log::error("Invalid role attempted: {$role}");
            return response()->json(['success' => false, 'message' => 'Invalid role.'], 400);
        }

        $user->role = $role;
        $saved = $user->save();
        
        Log::info("Role switch result for user {$user->id}: " . ($saved ? 'Success' : 'Failed'));

        $redirectUrl = $role === 'Funder' ? route('funder.dashboard') : route('citer.dashboard');

        return response()->json([
            'success' => true, 
            'message' => 'Dashboard role updated.',
            'redirect' => $redirectUrl
        ]);
    }

}
