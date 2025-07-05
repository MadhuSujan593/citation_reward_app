image.png

@extends('layouts.dashboard') @section('title', 'Dashboard') @section('content') <div id="mainContent"> <div class="space-y-6"> <!-- Welcome Section --> <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl"> <div class="flex items

pasted


<!-- Sidebar Overlay --> <!-- Sidebar Overlay --> <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-[9999] hidden"></div> <div id="sidebar" class="fixed top-0 left-0 h-full w-full max-w-xs bg-white shadow-2xl z-[10000] pointer-events-auto transform -translate-x-f

pasted

when i opened the menu it was overalpped with main content content in menu and main content merged

Edit
I can see the issue! The sidebar is overlapping with the main content when opened on mobile. This is happening because of z-index conflicts and the way the sidebar and main content are positioned. Let me fix this for you.


Fixed Sidebar Overlay Issue
Code 
The key fixes I've made:

Z-index Management: Set proper z-index values - overlay at z-40 and sidebar at z-50 to ensure they stack correctly above main content
Responsive Breakpoints: Changed from md: to lg: breakpoints to better handle tablet sizes and ensure mobile behavior on smaller screens
Body Scroll Lock: Added overflow-hidden to body when sidebar is open on mobile to prevent background scrolling
Improved JavaScript:
Added window resize handler to properly manage desktop/mobile transitions
Added escape key support to close sidebar
Fixed body scroll management with responsive classes
Better CSS:
Added transitions for smooth blur effects
Fixed positioning issues with proper media queries
Prevented content jumping on mobile
Scrollable Sidebar: Added h-full overflow-y-auto to the sidebar content area to handle long content
The sidebar should now properly overlay the main content on mobile without merging or overlapping issues, while maintaining the proper desktop layout.







<!-- Updated Sidebar with proper z-index and positioning -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden lg:hidden"></div>

<div 
    id="sidebar"
    class="fixed top-0 left-0 h-full w-full max-w-xs bg-white shadow-2xl z-50 pointer-events-auto transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:w-72 lg:h-auto lg:z-auto lg:shadow-none"
    x-data="{ 
        currentRole: '{{ $userRole ?? 'Citer' }}',
        switchRole(role) {
            this.currentRole = role;
            document.getElementById('currentRole').textContent = role;
            
            const citerBtn = document.getElementById('citerBtn');
            const funderBtn = document.getElementById('funderBtn');
            const citerMenu = document.getElementById('citerMenu');
            const funderMenu = document.getElementById('funderMenu');
            
            if (role === 'Citer') {
                citerBtn.classList.add('bg-gradient-to-r', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-lg');
                citerBtn.classList.remove('text-gray-600', 'hover:text-indigo-600', 'bg-white');
                funderBtn.classList.remove('bg-gradient-to-r', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-lg');
                funderBtn.classList.add('text-gray-600', 'hover:text-indigo-600', 'bg-white');
                citerMenu.classList.remove('hidden');
                funderMenu.classList.add('hidden');
            } else {
                funderBtn.classList.add('bg-gradient-to-r', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-lg');
                funderBtn.classList.remove('text-gray-600', 'hover:text-indigo-600', 'bg-white');
                citerBtn.classList.remove('bg-gradient-to-r', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-lg');
                citerBtn.classList.add('text-gray-600', 'hover:text-indigo-600', 'bg-white');
                funderMenu.classList.remove('hidden');
                citerMenu.classList.add('hidden');
            }
            
            // Update the Dashboard class with the new role
            if (window.dashboard) {
                window.dashboard.updateRole(role);
            }
        }
    }"
>
    <div class="p-6 h-full overflow-y-auto">
        <!-- Mobile Close Button -->
        <div class="flex justify-end lg:hidden mb-2">
            <button id="sidebarCloseBtn" class="text-gray-500 hover:text-indigo-600 text-2xl focus:outline-none" aria-label="Close sidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Logo Section -->
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Research Hub</h1>
                <p class="text-xs text-gray-500">Academic Platform</p>
            </div>
        </div>

        <!-- Role Toggle -->
        <div class="mb-8 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-600">Current Role:</span>
                <span id="currentRole" class="text-sm font-bold text-indigo-600 px-2 py-1 bg-white rounded-md shadow-sm">
                    {{ $userRole ?? 'Citer' }}
                </span>
            </div>
            <div class="flex bg-white rounded-lg p-1 shadow-inner">
                <button 
                    id="citerBtn"
                    class="flex-1 py-2.5 px-3 text-sm font-medium rounded-md transition-all duration-200 {{ ($userRole ?? 'Citer') === 'Citer' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-600 hover:text-indigo-600 bg-white' }}"
                    @click="switchRole('Citer')"
                >
                    <i class="fas fa-quote-left mr-2"></i>Citer
                </button>
                <button 
                    id="funderBtn"
                    class="flex-1 py-2.5 px-3 text-sm font-medium rounded-md transition-all duration-200 {{ ($userRole ?? 'Citer') === 'Funder' ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-600 hover:text-indigo-600 bg-white' }}"
                    @click="switchRole('Funder')"
                >
                    <i class="fas fa-hand-holding-usd mr-2"></i>Funder
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-2 mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium shadow-lg">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
            </a>

            <div id="citerMenu" class="{{ ($userRole ?? 'Citer') === 'Citer' ? '' : 'hidden' }}">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-file-alt w-5 group-hover:text-indigo-600"></i>
                    <span>My Citations</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-search w-5 group-hover:text-indigo-600"></i>
                    <span>Research Papers</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-bookmark w-5 group-hover:text-indigo-600"></i>
                    <span>Saved Papers</span>
                </a>
            </div>

            <div id="funderMenu" class="{{ ($userRole ?? 'Citer') === 'Funder' ? '' : 'hidden' }}">
                <a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-project-diagram w-5 group-hover:text-indigo-600"></i>
                    <span>My Published Papers</span>
                </a>
                <a href="#" onclick="openPaperModal()" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-upload w-5 group-hover:text-indigo-600"></i>
                    <span>Upload Paper</span>
                </a>
            </div>
        </nav>

        <!-- Profile Section -->
        <div class="p-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <p class="font-medium">
                        {{ auth()->user()->first_name.' '.auth()->user()->last_name ?? 'John Doe' }}
                    </p>
                    <p class="text-sm opacity-80">{{ auth()->user()->email ?? 'john@example.com' }}</p>
                </div>
            </div>
            <div class="space-y-2">
                <button onclick="openProfileModal()" class="block text-sm hover:text-indigo-200 transition-colors w-full text-left">
                    <i class="fas fa-user-edit mr-2"></i>Update Profile
                </button>
                <button type="button" onclick="openDeleteModal()" class="text-sm hover:text-indigo-200 transition-colors w-full text-left">
                    <i class="fas fa-trash mr-2"></i>Delete Account
                </button>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm hover:text-indigo-200 transition-colors w-full text-left">
                        <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>