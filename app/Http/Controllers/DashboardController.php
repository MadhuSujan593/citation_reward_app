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
}
