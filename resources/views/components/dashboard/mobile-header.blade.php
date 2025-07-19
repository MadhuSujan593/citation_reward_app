<div class="md:hidden fixed top-0 left-0 right-0 z-50 glass-effect border-b border-white/20 bg-white/95 backdrop-blur-xl">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center space-x-3 flex-1 min-w-0">
            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                @if(Request::is('wallet*'))
                    <i class="fas fa-wallet text-white text-sm"></i>
                @elseif(Request::is('dashboard*') || Request::is('/'))
                    <i class="fas fa-chart-line text-white text-sm"></i>
                @else
                    <i class="fas fa-chart-line text-white text-sm"></i>
                @endif
            </div>
            <h1 class="text-lg font-bold text-gray-800 truncate">
                @if(Request::is('wallet*'))
                    My Wallet
                @elseif(Request::is('dashboard*') || Request::is('/'))
                    Research Hub
                @else
                    {{ config('app.name', 'Research Hub') }}
                @endif
            </h1>
        </div>
        
        <button 
            id="hamburgerBtn" 
            class="p-3 rounded-xl bg-white/30 backdrop-blur-sm border border-white/40 hover:bg-white/40 transition-all duration-200 flex-shrink-0 shadow-sm"
        >
            <i class="fas fa-bars text-gray-700 text-lg"></i>
        </button>
    </div>
</div> 