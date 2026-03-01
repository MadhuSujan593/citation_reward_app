@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('content')
<div id="mainContent">
<div class="space-y-6">
    <!-- Simple Welcome Header (Minimalist) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Welcome, {{ auth()->user()->first_name }}</h1>
            <p class="text-sm text-slate-500" data-welcome-message>
                {{ trim(auth()->user()->role ?? 'Citer') === 'Citer' ? 'Explore research papers and manage your citations.' : 'Control your publications and track citation growth.' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-slate-400">Viewing as</span>
            <span class="px-3 py-1 bg-white border border-blue-100 rounded-full text-[10px] font-bold text-blue-600 shadow-sm" id="currentRoleDisplay">
                {{ auth()->user()->role ?? 'Citer' }}
            </span>
        </div>
    </div>

    <!-- Stats Overview -->


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 mb-1">Total papers</p>
                    <h3 class="text-2xl font-bold text-slate-800" id="totalPapers">0</h3>
                </div>
                <div class="w-9 h-9 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-sm"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 mb-1" data-stat="citations">
                        {{ trim(auth()->user()->role ?? 'Citer') === 'Citer' ? 'My citations' : 'Total citations' }}
                    </p>
                    <h3 class="text-2xl font-bold text-slate-800" id="totalCitations">0</h3>
                </div>
                <div class="w-9 h-9 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-quote-left text-sm"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Papers Section -->
    <x-dashboard.papers-section :currentRole="trim(auth()->user()->role ?? 'Citer')" />

    <!-- Pagination -->
    <div id="paginationContainer" class="flex justify-center mt-8 pb-10"></div>
</div>
</div>
@endsection

@push('modals')
    <x-dashboard.modals.profile-modal />
    <x-dashboard.modals.paper-details-modal />
    <x-dashboard.modals.edit-paper-modal />
    <x-dashboard.modals.delete-paper-modal />
    <x-dashboard.modals.delete-confirmation-modal />
    <x-dashboard.modals.citation-confirmation-modal />
@endpush

@push('scripts')



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check URL parameters for auto-actions
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('citations') === '1') {
        // Wait for dashboard to be initialized, then load citations
        setTimeout(function() {
            if (window.dashboard && typeof window.dashboard.loadMyCitations === 'function') {
                window.dashboard.loadMyCitations();
                // Clean up URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }, 500);
    } else if (urlParams.get('explore') === '1') {
        // Wait for dashboard to be initialized, then load all papers
        setTimeout(function() {
            if (window.dashboard && typeof window.dashboard.loadPapers === 'function') {
                window.dashboard.loadPapers();
                // Clean up URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }, 500);
    }
});
</script>
@endpush
