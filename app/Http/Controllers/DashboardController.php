<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $viewType = session('view_type', 'citer'); // Default to 'citer'
        return view('dashboard', compact('viewType'));
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
        $role = $request->input('role'); 
        $user = Auth::user();

        if ($role === 'fund') {
            $papers = PublishedPaper::where('user_id', $user->id)->latest()->get();
        } elseif ($role === 'citer') {
            $papers = PublishedPaper::where('user_id', '!=', $user->id)->latest()->get();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'papers' => $papers
        ]);
}
