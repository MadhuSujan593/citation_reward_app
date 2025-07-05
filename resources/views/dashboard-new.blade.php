@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div id="mainContent">
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
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
    </div>

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
<script src="{{ asset('js/dashboard.js') }}"></script>

<script>
    // Additional dashboard-specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Update stats when papers are loaded
        
        const updateStats = () => {
            const dashboard = window.dashboard;
            if (!dashboard) return;

            const totalPapers = dashboard.papers?.length || 0;
            const currentRole = dashboard.currentRole;
            
            let totalCitations = 0;
            if (currentRole === 'Citer') {
                // For Citer: count papers they have cited
                totalCitations = dashboard.papers?.filter(p => p.is_paper_cited_by_current_user)?.length || 0;
            } else {
                // For Funder: count total citations of their papers (mock data for now)
                totalCitations = Math.floor(Math.random() * totalPapers) + (totalPapers > 0 ? 1 : 0);
            }
            
            document.getElementById('totalPapers').textContent = totalPapers;
            document.getElementById('totalCitations').textContent = totalCitations;
            document.getElementById('recentActivity').textContent = Math.floor(Math.random() * 10) + 1; // Mock data
        };

        // Update stats after papers are loaded
        setTimeout(updateStats, 1000);

        // Listen for role changes to update stats
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.target.id === 'currentRole') {
                    setTimeout(updateStats, 100); // Update stats after role change
                }
            });
        });

        const currentRoleElement = document.getElementById('currentRole');
        if (currentRoleElement) {
            observer.observe(currentRoleElement, { childList: true, subtree: true });
        }
    });

    // Global functions for backward compatibility
    function openDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }

    function confirmDelete() {
        closeDeleteModal();
        
        fetch('{{ route('profile.del') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.dashboard?.showToast(data.message);
                setTimeout(() => {
                    window.location.href = '{{ route('login') }}';
                }, 2000);
            } else {
                window.dashboard?.showToast(data.message || 'Failed to delete account.', true);
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            window.dashboard?.showToast('Something went wrong.', true);
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
    
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            if (mainContent) {
                mainContent.classList.add('blur-sm', 'lg:blur-none');
            }
        }
    
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            if (mainContent) {
                mainContent.classList.remove('blur-sm');
            }
        }
    
        // Handle window resize to ensure proper behavior
        function handleResize() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                // Desktop: hide overlay and enable body scroll
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                if (mainContent) {
                    mainContent.classList.remove('blur-sm');
                }
            }
        }
    
        if (hamburgerBtn && sidebar && sidebarOverlay) {
            hamburgerBtn.addEventListener('click', openSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
    
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', closeSidebar);
        }
    
        // Listen for window resize
        window.addEventListener('resize', handleResize);
    
        // Handle escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !sidebarOverlay.classList.contains('hidden')) {
                closeSidebar();
            }
        });
    });
    </script>

    <!-- ADD THE CSS HERE -->
<style>
    /* Ensure proper z-index stacking */
    #mainContent { 
        position: relative; 
        z-index: 1; 
    }
    
    /* Prevent content from jumping on mobile */
    @media (max-width: 1023px) {
        #mainContent {
            transition: filter 0.3s ease;
        }
        
        body.overflow-hidden {
            position: fixed;
            width: 100%;
            height: 100%;
        }
    }
    
    /* Ensure sidebar doesn't interfere with desktop layout */
    @media (min-width: 1024px) {
        #sidebar {
            position: relative !important;
            transform: translateX(0) !important;
            z-index: auto !important;
        }
        
        #sidebarOverlay {
            display: none !important;
        }
    }
    </style>
@endpush 