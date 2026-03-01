<!-- Updated Sidebar with proper z-index and positioning -->

<div 
    id="sidebar"
    class="fixed top-0 left-0 h-full w-full max-w-xs bg-white shadow-2xl z-50 pointer-events-auto transform -translate-x-full transition-transform duration-300 lg:sticky lg:top-0 lg:translate-x-0 lg:w-72 lg:h-screen lg:z-auto lg:shadow-none"
    x-data="{ 
        currentRole: '{{ auth()->user()->role ?? 'Citer' }}',
        isAdmin: {{ auth()->user()->role === 'Admin' ? 'true' : 'false' }},
        switchRole(role) {
            this.currentRole = role;
            if (document.getElementById('currentRole')) {
                document.getElementById('currentRole').textContent = role;
            }
            
            // Update the Dashboard class with the new role
            if (window.dashboard) {
                window.dashboard.updateRole(role);
            } else {
                // Fallback for pages without window.dashboard
                fetch('/dashboard/switch-role', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ role: role })
                }).then(res => res.json()).then(data => {
                    if (data.redirect) window.location.href = data.redirect;
                });
            }
        }
    }"
>
    <div class="px-6 py-8 h-full flex flex-col overflow-y-auto">
        <!-- Logo Section -->
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #2563eb !important;">
                    <i class="fas fa-layer-group text-lg" style="color: white !important;"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Citation Hub</h1>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button id="closeSidebarBtn" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Role Selector (Removed) -->

        <!-- Navigation Menu -->
        <nav class="space-y-4">
            @php
                $userRole = auth()->user()->role ?? 'Citer';
                if ($userRole === 'Admin') {
                    $dashboardRoute = route('admin.dashboard');
                } else {
                    $dashboardRoute = $userRole === 'Funder' ? route('funder.dashboard') : route('citer.dashboard');
                }
                $isDashboardActive = Request::routeIs('dashboard') || Request::routeIs('citer.dashboard') || Request::routeIs('funder.dashboard') || Request::routeIs('admin.dashboard');
            @endphp
            <div class="space-y-1">
                <a href="{{ $dashboardRoute }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl font-bold transition-all {{ $isDashboardActive ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fas fa-th-large w-5 {{ $isDashboardActive ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div x-cloak class="space-y-1">
                <a href="{{ route('wallet.index') }}" 
                    class="flex items-center space-x-3 px-4 py-2.5 rounded-xl font-bold transition-all {{ Request::is('wallet*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }}"
                >
                    <i class="fas fa-wallet w-5 {{ Request::is('wallet*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Wallet</span>
                </a>
            </div>

            <div x-show="currentRole === 'Citer'" x-cloak class="space-y-1">
                <a href="javascript:void(0);" onclick="handleMyCitationsClick()"
                    class="flex items-center space-x-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-all font-bold">
                    <i class="fas fa-quote-right w-5 text-slate-400"></i>
                    <span>My Citations</span>
                </a>
            </div>

            <div x-show="currentRole === 'Citer'" x-cloak class="space-y-1">
                <a href="javascript:void(0);" onclick="handleExplorePapersClick()"
                    class="flex items-center space-x-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-all font-bold">
                    <i class="fas fa-compass w-5 text-slate-400"></i>
                    <span>Explore Papers</span>
                </a>
            </div>

            <div x-show="currentRole === 'Citer'" x-cloak class="space-y-1">
                <a href="{{ route('claim-requests.index') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ Request::is('claim-requests*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl transition-all font-bold">
                    <i class="fas fa-file-invoice-dollar w-5 {{ Request::is('claim-requests*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Claims</span>
                </a>
            </div>

            @if(auth()->user()->role === 'Admin')
                <div class="space-y-1">
                    <a href="{{ route('admin.claim-requests') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ Request::is('admin/claim-requests*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl transition-all font-bold">
                        <i class="fas fa-tasks w-5 {{ Request::is('admin/claim-requests*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Manage Claims</span>
                    </a>
                </div>
                
                <div class="space-y-1">
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ Request::is('admin/users*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl transition-all font-bold">
                        <i class="fas fa-users w-5 {{ Request::is('admin/users*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Manage Users</span>
                    </a>
                </div>
            @endif

            @if(auth()->user()->role !== 'Admin')
                <div x-show="currentRole === 'Funder'" x-cloak class="space-y-1">
                    <a href="{{ route('papers.create') }}" 
                        class="flex items-center space-x-3 px-4 py-2.5 {{ Request::is('upload-paper') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl transition-all font-bold">
                        <i class="fas fa-plus-circle w-5 {{ Request::is('upload-paper') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Upload Paper</span>
                    </a>
                </div>
            @endif
        </nav>



        <!-- Profile Section -->
        <div class="mt-8 pt-6 border-t border-slate-100 pb-8">
            <div class="flex items-center space-x-3 px-2 mb-4">
                <div class="w-9 h-9 bg-slate-200 rounded-full flex items-center justify-center overflow-hidden border-2 border-slate-200">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-slate-400"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->first_name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button onclick="openProfileModal()" class="flex items-center justify-center p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Settings">
                    <i class="fas fa-cog"></i>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Sign Out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>