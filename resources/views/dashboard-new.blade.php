@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div id="mainContent">
<div class="space-y-6">
    <!-- Welcome Section -->
    {{-- <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}!</h1>
                <p class="text-indigo-100" data-welcome-message>
                    {{ ($userRole ?? 'Citer') === 'Citer' ? 'Manage your research citations and discover new papers.' : 'Manage your published papers and track citations.' }}
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Papers</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalPapers">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600" data-stat="citations">
                        {{ ($userRole ?? 'Citer') === 'Citer' ? 'My Citations' : 'Total Citations' }}
                    </p>
                    <p class="text-2xl font-bold text-gray-800" id="totalCitations">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-quote-left text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recent Activity</p>
                    <p class="text-2xl font-bold text-gray-800" id="recentActivity">0</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Papers Section -->
    <x-dashboard.papers-section :currentRole="$userRole ?? 'Citer'" />
</div>
</div>
@endsection

@push('modals')
    <x-dashboard.modals.profile-modal />
    <x-dashboard.modals.paper-details-modal />
    <x-dashboard.modals.upload-paper-modal />
    <x-dashboard.modals.edit-paper-modal />
    <x-dashboard.modals.delete-paper-modal />
    <x-dashboard.modals.delete-confirmation-modal />
    <x-dashboard.modals.citation-confirmation-modal />
@endpush

@push('scripts')

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush