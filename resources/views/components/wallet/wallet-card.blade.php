@props(['wallet', 'showActions' => true])

<div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Wallet Balance</h2>
        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
            <i class="fas fa-wallet text-white text-lg"></i>
        </div>
    </div>
    
    <div class="mb-6">
        <div class="text-4xl font-bold text-gray-800 mb-2">
            {{ $wallet->formatted_balance }}
        </div>
        <p class="text-gray-600">Available funds for research rewards</p>
    </div>

    @if($showActions)
        <div class="flex flex-wrap gap-3">
            <button 
                onclick="openAddFundsModal()"
                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 transform hover:scale-105"
            >
                <i class="fas fa-plus"></i>
                <span>Add Funds</span>
            </button>
            <button 
                onclick="refreshWallet()"
                class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200"
            >
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
            </button>
        </div>
    @endif
</div> 