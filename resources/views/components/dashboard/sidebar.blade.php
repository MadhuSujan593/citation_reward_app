<!-- Updated Sidebar with proper z-index and positioning -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden lg:hidden"></div>

<div 
    id="sidebar"
    class="fixed top-0 left-0 h-full w-full max-w-xs bg-white shadow-2xl z-50 pointer-events-auto transform -translate-x-full transition-transform duration-300 lg:sticky lg:top-0 lg:translate-x-0 lg:w-72 lg:h-screen lg:z-auto lg:shadow-none"
    x-data="{ 
        currentRole: '{{ auth()->user()->role ?? 'Citer' }}',
        switchRole(role) {
            this.currentRole = role;
            if (document.getElementById('currentRole')) {
                document.getElementById('currentRole').textContent = role;
            }
            
            // Update the Dashboard class with the new role
            if (window.dashboard) {
                window.dashboard.updateRole(role);
            }

            // If on claim request page, redirect when switching to Funder
            if (window.location.pathname.includes('/claim-requests')) {
                if (role === 'Funder') {
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
            }

            // If on wallet page, redirect when switching to Citer
            if (window.location.pathname.includes('/wallet')) {
                if (role === 'Citer') {
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
            }
        }
    }"
>
    <div class="px-6 py-8 h-full flex flex-col overflow-y-auto">
        <!-- Logo Section -->
        <div class="flex items-center space-x-3 mb-10">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg shadow-blue-100" style="background-color: #2563eb !important;">
                <i class="fas fa-layer-group text-lg" style="color: white !important;"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">ResearchHub</h1>
            </div>
        </div>

        <!-- Role Selector -->
        <div class="mb-10 px-4">
            <p class="text-[11px] font-bold text-slate-400 mb-3 uppercase tracking-widest">Workspace Role</p>
            @if(auth()->user()->email === 'admin@citationapp.com')
                <div class="p-3 rounded-2xl flex items-center gap-3 border shadow-lg" style="background-color: #0f172a; border-color: #1e293b;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: #2563eb;">
                        <i class="fas fa-shield-alt text-white text-xs"></i>
                    </div>
                    <span class="text-xs font-bold text-white uppercase tracking-widest">Super Admin</span>
                </div>
            @else
                <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-1 border border-gray-200">
                    <button 
                        class="flex-1 py-2.5 px-3 text-xs font-bold rounded-xl transition-all duration-300"
                        :style="currentRole === 'Citer' ? 'background-color: #2563eb !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);' : 'background-color: transparent; color: #64748b;'"
                        @click="switchRole('Citer')"
                    >
                        <i class="fas fa-quote-right mr-1.5" :style="currentRole === 'Citer' ? 'color: white !important;' : 'color: #94a3b8;'"></i> Citer
                    </button>
                    <button 
                        class="flex-1 py-2.5 px-3 text-xs font-bold rounded-xl transition-all duration-300"
                        :style="currentRole === 'Funder' ? 'background-color: #2563eb !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);' : 'background-color: transparent; color: #64748b;'"
                        @click="switchRole('Funder')"
                    >
                        <i class="fas fa-briefcase mr-1.5" :style="currentRole === 'Funder' ? 'color: white !important;' : 'color: #94a3b8;'"></i> Funder
                    </button>
                </div>
            @endif
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-6">
            <div>
                <p class="px-4 text-[11px] font-semibold text-slate-400 mb-2 uppercase tracking-wider">Main</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl font-bold transition-all {{ Request::routeIs('dashboard') || Request::routeIs('admin.claim-requests') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fas fa-th-large w-5 {{ Request::routeIs('dashboard') || Request::routeIs('admin.claim-requests') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('wallet.index') }}" 
                        x-show="currentRole === 'Funder'"
                        class="flex items-center space-x-3 px-4 py-2.5 rounded-xl font-bold transition-all {{ Request::is('wallet*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }}"
                    >
                        <i class="fas fa-wallet w-5 {{ Request::is('wallet*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Wallet</span>
                    </a>
                </div>
            </div>

            <div x-show="currentRole === 'Citer'" x-cloak>
                <p class="px-4 text-[11px] font-semibold text-slate-400 mb-2 uppercase tracking-wider">Citer Tools</p>
                <div class="space-y-1">
                    <a href="javascript:void(0);" onclick="handleMyCitationsClick()"
                        class="flex items-center space-x-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-all font-bold">
                        <i class="fas fa-quote-right w-5 text-slate-400"></i>
                        <span>My Citations</span>
                    </a>
                    <a href="javascript:void(0);" onclick="handleExplorePapersClick()"
                        class="flex items-center space-x-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-all font-bold">
                        <i class="fas fa-compass w-5 text-slate-400"></i>
                        <span>Explore Papers</span>
                    </a>
                    <a href="{{ route('claim-requests.index') }}" class="flex items-center space-x-3 px-4 py-2.5 {{ Request::is('claim-requests*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl transition-all font-bold">
                        <i class="fas fa-file-invoice-dollar w-5 {{ Request::is('claim-requests*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                        <span>Claims</span>
                    </a>
                </div>
            </div>

            @if(auth()->user()->email !== 'admin@citationapp.com')
                <div x-show="currentRole === 'Funder'" x-cloak>
                    <p class="px-4 text-[11px] font-semibold text-slate-400 mb-2 uppercase tracking-wider">Funder Tools</p>
                    <div class="space-y-1">
                        <a href="javascript:void(0);" onclick="if(window.location.pathname === '{{ route('dashboard', [], false) }}'){ openPaperModal(); } else { window.location.href='{{ route('dashboard') }}?upload=1'; }" 
                            class="flex items-center space-x-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-all font-bold">
                            <i class="fas fa-plus-circle w-5 text-slate-400"></i>
                            <span>Upload Paper</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>



        <!-- Profile Section -->
        <div class="mt-8 pt-6 border-t border-slate-100 pb-8">
            <div class="flex items-center space-x-3 px-2 mb-4">
                <div class="w-9 h-9 bg-slate-200 rounded-full flex items-center justify-center overflow-hidden">
                    <i class="fas fa-user text-slate-400"></i>
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