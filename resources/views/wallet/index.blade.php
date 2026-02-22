@extends('layouts.dashboard')

@section('title', 'My Wallet')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">My Wallet</h1>
            <p class="text-sm text-slate-500">Manage your research funding and transactions</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-wallet text-slate-900"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Balance Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_4px_rgba(0,0,0,0.02),0_10px_20px_rgba(0,0,0,0.03)] p-8 h-full relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-100 group-hover:bg-slate-900 transition-colors"></div>
                
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-900 border border-slate-100">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Available Balance</p>
                        <h2 class="text-3xl font-bold text-slate-900" id="walletBalance">
                            {{ number_format($wallet->balance, 0) }}
                        </h2>
                    </div>
                </div>

                @if(Auth::user()->role === 'Funder' || Auth::user()->email === 'admin@citationapp.com')
                <div class="flex gap-3">
                    <button onclick="openAddFundsModal()" class="w-auto px-10 h-11 flex items-center justify-center gap-2 text-sm font-bold text-white bg-blue-600 rounded-xl transition-all shadow-sm">
                        <i class="fas fa-plus"></i>
                        Add Funds
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 mb-1">Total Credited</p>
                <p class="text-xl font-bold text-emerald-600">
                    {{ number_format($stats['total_credited'], 0) }}
                </p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 mb-1">Total Debited</p>
                <p class="text-xl font-bold text-rose-500">
                    {{ number_format($stats['total_debited'], 0) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-2">
                <i class="fas fa-history text-slate-400 text-sm"></i>
                <h3 class="font-bold text-slate-800">Recent Transactions</h3>
            </div>
            <button onclick="loadAllTransactions()" class="text-xs font-bold text-slate-900 hover:underline">
                View All
            </button>
        </div>

        <div id="transactionsContainer" class="p-2">
            @if($transactions->count() > 0)
                <div class="space-y-1">
                    @foreach($transactions as $transaction)
                        <div class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->type === 'credit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                                    <i class="fas {{ $transaction->type === 'credit' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 truncate max-w-[200px] sm:max-w-xs">{{ $transaction->description }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold" style="color: {{ $transaction->type === 'credit' ? '#059669' : '#e11d48' }} !important;">
                                    {{ $transaction->formatted_amount }}
                                </p>
                                <p class="text-[10px] text-slate-400 font-medium">Balance: {{ $transaction->formatted_balance_after }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="fas fa-receipt text-slate-300 text-xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No transactions yet</p>
                    <p class="text-xs text-slate-400 mt-1">Activities will appear here once you start using the wallet</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div id="addFundsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="addFundsModalContent">
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100 flex-shrink-0">
                        <i class="fa-solid fa-plus text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Add Funds</h3>
                </div>
                <button onclick="closeAddFundsModal()" class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors flex-shrink-0">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="addFundsForm" class="space-y-4 sm:space-y-6">
                @csrf
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">
                        Amount to Add
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold text-sm sm:text-base"><i class="fas fa-coins text-blue-500"></i></span>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            min="1" 
                            max="10000" 
                            step="1"
                            class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 text-sm sm:text-base font-bold"
                            placeholder="0"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-3">Description (Optional)</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-3 sm:px-4 py-3 sm:py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 resize-none text-sm sm:text-base"
                        placeholder="e.g., Research funding for Q1 2024"
                    ></textarea>
                </div>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4 sm:pt-6">
                    <button 
                        type="button"
                        onclick="closeAddFundsModal()"
                        class="flex-1 px-4 sm:px-6 py-3 sm:py-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-2xl font-semibold transition-all duration-200 text-sm sm:text-base"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 sm:px-6 py-3 sm:py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-sm transition-all duration-200 text-sm sm:text-base"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add INR</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- All Transactions Modal -->
<div id="allTransactionsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-3xl transform transition-all duration-300 scale-95 opacity-0 flex flex-col" id="allTransactionsModalContent" style="height: 70vh; max-height: 600px;">
        <!-- Mobile drag handle -->
        <div class="sm:hidden w-10 h-1 bg-gray-300 rounded-full mx-auto mt-3 mb-1 flex-shrink-0"></div>
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 sm:p-5 flex-shrink-0 border-b border-gray-100 bg-white rounded-t-3xl sm:rounded-t-3xl">
            <div class="flex items-center space-x-3 min-w-0 flex-1">
                <div class="w-10 h-10 sm:w-11 sm:h-11 bg-gradient-to-br from-gray-700 to-gray-900 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 truncate">All Transactions</h3>
                    <p class="text-xs sm:text-sm text-gray-500">Your complete transaction history</p>
                </div>
            </div>
            <button onclick="closeAllTransactionsModal()" class="w-8 h-8 sm:w-9 sm:h-9 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors flex-shrink-0 ml-3">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Modal Content - Scrollable Area -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <div id="allTransactionsContainer" class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                <!-- Transactions will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('modals')
    <x-dashboard.modals.profile-modal />
    <x-dashboard.modals.delete-confirmation-modal />
@endpush

@push('scripts')
<script>
// Pass wallet data to JavaScript
const walletData = {
    currency: '{{ $wallet->currency }}',
    currencySymbol: '{{ $wallet->currency_symbol }}',
    balance: {{ $wallet->balance }}
};

class WalletManager {
    constructor() {
        this.currency = walletData.currency;
        this.currencySymbol = walletData.currencySymbol;
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Add funds form submission
        document.getElementById('addFundsForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.addFunds();
        });
    }

    async addFunds() {
        const form = document.getElementById('addFundsForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        try {
            submitBtn.innerHTML = '<span class="flex items-center justify-center space-x-2"><i class="fas fa-spinner fa-spin"></i><span>Adding...</span></span>';
            submitBtn.disabled = true;

            const response = await fetch('{{ route("wallet.add-funds") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: formData.get('amount'),
                    description: formData.get('description')
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, false);
                this.updateWalletBalance(data.wallet.raw_balance);
                closeAddFundsModal();
                form.reset();
                // Refresh the page to show new transaction
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showToast(data.message || 'Failed to add funds', true);
            }
        } catch (error) {
            console.error('Error adding funds:', error);
            this.showToast('An error occurred while adding funds', true);
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    async loadAllTransactions() {
        const container = document.getElementById('allTransactionsContainer');
        
        // Show loading state
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center text-center py-16">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-spinner fa-spin text-white text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Loading transactions...</h3>
                <p class="text-gray-500 text-sm">Please wait...</p>
            </div>
        `;
        
        try {
            const response = await fetch('{{ route("wallet.transactions") }}');
            const data = await response.json();

            if (data.success) {
                this.displayAllTransactions(data.transactions.data);
            } else {
                this.showToast('Failed to load transactions', true);
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center text-center py-16">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-200 to-red-300 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Failed to load</h3>
                        <p class="text-gray-500 text-sm">Please try again later</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading transactions:', error);
            this.showToast('An error occurred while loading transactions', true);
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center text-center py-16">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-200 to-red-300 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-wifi text-red-500 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Connection error</h3>
                    <p class="text-gray-500 text-sm">Check your connection and try again</p>
                </div>
            `;
        }
    }

    displayAllTransactions(transactions) {
        const container = document.getElementById('allTransactionsContainer');
        
        if (transactions.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center text-center py-16">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No transactions found</h3>
                    <p class="text-gray-500 text-sm px-4">Start using your wallet to see transactions here</p>
                </div>
            `;
            return;
        }

        const transactionsHtml = transactions.map(transaction => `
            <div class="group bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-10 h-10 ${transaction.type === 'credit' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'} rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                            <i class="${transaction.type === 'credit' ? 'fas fa-arrow-up' : 'fas fa-arrow-down'} text-xs font-bold"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-gray-900 text-sm mb-0.5 truncate">${transaction.description}</h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1" style="font-size: 10px;"></i>
                                <span>${new Date(transaction.created_at).toLocaleString('en-US', { 
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                })}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end ml-3">
                        <div class="text-base font-bold" style="color: ${transaction.type === 'credit' ? '#059669' : '#e11d48'} !important;">
                            ${transaction.type === 'credit' ? '+' : '-'}${Math.abs(parseFloat(transaction.amount)).toFixed(0)} INR
                        </div>
                        <div class="text-xs text-gray-500">
                            Balance: ${parseFloat(transaction.balance_after).toFixed(0)} INR
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = transactionsHtml;
    }

    updateWalletBalance(balance) {
        const walletBalanceEl = document.getElementById('walletBalance');
        const numericBalance = parseFloat(balance);

        if (!isNaN(numericBalance)) {
            walletBalanceEl.textContent = numericBalance.toLocaleString();
        } else {
            walletBalanceEl.textContent = '0';
            console.warn('Invalid balance received:', balance);
        }
    }

    showToast(message, isError = false) {
        // Use Dashboard's showToast method for consistency
        if (window.dashboard && window.dashboard.showToast) {
            window.dashboard.showToast(message, isError);
        } else {
            console.log('Dashboard showToast not available:', message);
        }
    }

}

function openAddFundsModal() {
    const modal = document.getElementById('addFundsModal');
    const content = document.getElementById('addFundsModalContent');

    modal.classList.remove('hidden');

    requestAnimationFrame(() => {
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    });
}

function closeAddFundsModal() {
    const modal = document.getElementById('addFundsModal');
    const content = document.getElementById('addFundsModalContent');
    
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openAllTransactionsModal() {
    const modal = document.getElementById('allTransactionsModal');
    const content = document.getElementById('allTransactionsModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Load transactions
    walletManager.loadAllTransactions();
}

function closeAllTransactionsModal() {
    const modal = document.getElementById('allTransactionsModal');
    const content = document.getElementById('allTransactionsModalContent');
    
    content.classList.add('scale-95', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function loadAllTransactions() {
    openAllTransactionsModal();
}

function refreshWallet() {
    location.reload();
}

// Initialize wallet manager
const walletManager = new WalletManager();

// Initialize minimal dashboard for profile functionality only
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        // Use existing Dashboard class but prevent papers loading for wallet page
        if (typeof Dashboard !== 'undefined') {
            try {
                // Create Dashboard instance (now won't load papers because of dashboard.js check)
                
                // Create Dashboard instance (now won't load papers because of dashboard.js check)
                window.dashboard = new Dashboard();
                console.log('Dashboard initialized for wallet page');
            } catch (error) {
                console.warn('Dashboard initialization failed:', error);
            }
        }
    }, 100);
});

// Immediately show fallback icon, then try FontAwesome
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('debit-icon-container');
    if (container) {
        // Check if mobile
        const isMobile = window.innerWidth < 640;
        const size = isMobile ? '40px' : '48px';
        const fontSize = isMobile ? '16px' : '20px';
        
        // Immediately apply fallback
        container.style.cssText = `
            width: ${size} !important;
            height: ${size} !important;
            background: linear-gradient(to bottom right, rgb(148, 163, 184), rgb(75, 85, 99)) !important;
            border-radius: 1rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            position: relative !important;
            overflow: visible !important;
        `;
        
        container.innerHTML = `
            <span style="
                color: white !important;
                font-size: ${fontSize} !important;
                font-weight: bold !important;
                font-family: system-ui, -apple-system, sans-serif !important;
                display: block !important;
                text-align: center !important;
                line-height: 1 !important;
                z-index: 10 !important;
                position: relative !important;
            ">−</span>
        `;
    }
});

// Close modals when clicking outside
document.addEventListener('click', (e) => {
    if (e.target.id === 'addFundsModal') {
        closeAddFundsModal();
    }
    if (e.target.id === 'allTransactionsModal') {
        closeAllTransactionsModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Auto-open upload modal if ?upload=1 is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('upload') === '1') {
        if (typeof openPaperModal === 'function') {
            openPaperModal();
        }
    }
    // If you use #upload-paper instead:
    if (window.location.hash === '#upload-paper') {
        if (typeof openPaperModal === 'function') {
            openPaperModal();
        }
    }
});


</script>
@endpush