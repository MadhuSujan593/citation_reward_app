@extends('layouts.dashboard')

@section('title', 'My Wallet')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    My Wallet
                </h1>
                <p class="text-gray-600 mt-1">Manage your research funding</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Balance Card - Takes up 2 columns -->
            <div class="xl:col-span-2">
                <div class="group relative bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-emerald-500/10 to-teal-500/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Available Balance</h2>
                                    <p class="text-sm text-gray-500">Ready for research rewards</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-8">
                            <div class="text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2" id="walletBalance">
                                {{ $wallet->currency_symbol }}{{ number_format($wallet->balance, 2) }}
                            </div>
                            <div class="w-24 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <button 
                                id="addFundsBtn"
                                onclick="openAddFundsModal()"
                                class="group flex-1 relative overflow-hidden bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                            >
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                    <span>Add Funds</span>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                            
                            <button 
                                onclick="refreshWallet()"
                                class="group relative overflow-hidden bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:-translate-y-1"
                            >
                                <span class="flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-rotate-right text-sm group-hover:rotate-180 transition-transform duration-300"></i>
                                    <span>Refresh</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid - Takes up 2 columns -->
            <div class="xl:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Total Credited -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-arrow-trend-up text-white"></i>
                        </div>
                        <div class="text-indigo-500 text-sm font-medium bg-indigo-50 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-up mr-1"></i>
                            Credit
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Credited</p>
                        <p class="text-2xl font-bold text-indigo-600">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_credited'], 2) }}
                        </p>
                    </div>
                </div>

                <!-- Total Debited -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <!-- ICON IN COLORED SQUARE -->
                        <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-gray-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-arrow-down text-white"></i>
                        </div>
                        <div class="text-slate-600 text-sm font-medium bg-slate-100 px-3 py-1 rounded-full">
                            Debit
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Debited</p>
                        <p class="text-2xl font-bold text-slate-700">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_debited'], 2) }}
                        </p>
                    </div>
                </div>

                <!-- Transaction Count -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-arrow-right-arrow-left text-white"></i>
                        </div>
                        <div class="text-blue-500 text-sm font-medium bg-blue-50 px-3 py-1 rounded-full">
                            Total
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Transactions</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['transaction_count'] }}</p>
                    </div>
                </div>

                <!-- This Month -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-calendar-days text-white"></i>
                        </div>
                        <div class="text-purple-500 text-sm font-medium bg-purple-50 px-3 py-1 rounded-full">
                            Monthly
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm mb-1">This Month</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ is_numeric($stats['this_month']) ? $wallet->currency_symbol . number_format($stats['this_month'], 2) : $stats['this_month'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/20">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Recent Transactions</h2>
                        <p class="text-gray-600 text-sm">Your latest financial activities</p>
                    </div>
                </div>
                <button 
                    onclick="loadAllTransactions()"
                    class="group flex items-center space-x-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                >
                    <span>View All</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </button>
            </div>

            <div id="transactionsContainer">
                @if($transactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($transactions as $transaction)
                            <div class="group flex items-center justify-between p-6 bg-gradient-to-r from-gray-50/80 to-white/80 backdrop-blur-sm rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-100/50">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-14 {{ $transaction->type === 'credit' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-rose-600' }} rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="{{ $transaction->type === 'credit' ? 'fas fa-plus text-white' : 'fas fa-minus text-white' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 mb-1">{{ $transaction->description }}</p>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            {{ $transaction->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }} mb-1">
                                        {{ $transaction->formatted_amount }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Balance: {{ $transaction->formatted_balance_after }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No transactions yet</h3>
                        <p class="text-gray-500">Add funds to get started with your research funding</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div id="addFundsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="addFundsModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-plus text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Add Funds</h3>
                </div>
                <button onclick="closeAddFundsModal()" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="addFundsForm" class="space-y-6">
                @csrf
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-3">
                        Amount ({{ $wallet->currency }})
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">{{ $wallet->currency_symbol }}</span>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            min="1" 
                            max="10000" 
                            step="0.01"
                            class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50/50"
                            placeholder="0.00"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">Description (Optional)</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 resize-none"
                        placeholder="e.g., Research funding for Q1 2024"
                    ></textarea>
                </div>

                <div class="flex space-x-4 pt-6">
                    <button 
                        type="button"
                        onclick="closeAddFundsModal()"
                        class="flex-1 px-6 py-4 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-2xl font-semibold transition-all duration-200"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1"
                    >
                        <span class="flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Funds</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- All Transactions Modal -->
<div id="allTransactionsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[80vh] transform transition-all duration-300 scale-95 opacity-0" id="allTransactionsModalContent">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">All Transactions</h3>
                </div>
                <button onclick="closeAllTransactionsModal()" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="allTransactionsContainer" class="max-h-96 overflow-y-auto">
                <!-- Transactions will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-6 right-6 z-[9999] px-6 py-4 rounded-2xl shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-x-full">
    <span id="toastMessage" class="font-medium"></span>
</div>
@endsection

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
        try {
            const response = await fetch('{{ route("wallet.transactions") }}');
            const data = await response.json();

            if (data.success) {
                this.displayAllTransactions(data.transactions.data);
            } else {
                this.showToast('Failed to load transactions', true);
            }
        } catch (error) {
            console.error('Error loading transactions:', error);
            this.showToast('An error occurred while loading transactions', true);
        }
    }

    displayAllTransactions(transactions) {
        const container = document.getElementById('allTransactionsContainer');
        
        if (transactions.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No transactions found</h3>
                    <p class="text-gray-500">Your transaction history will appear here</p>
                </div>
            `;
            return;
        }

        const transactionsHtml = transactions.map(transaction => `
            <div class="group flex items-center justify-between p-6 bg-gradient-to-r from-gray-50/80 to-white/80 backdrop-blur-sm rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-100/50 mb-4">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 ${transaction.type === 'credit' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-rose-600'} rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="${transaction.type === 'credit' ? 'fas fa-plus text-white' : 'fas fa-minus text-white'}"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 mb-1">${transaction.description}</p>
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            ${new Date(transaction.created_at).toLocaleDateString('en-US', { 
                                year: 'numeric', 
                                month: 'short', 
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold ${transaction.type === 'credit' ? 'text-green-600' : 'text-red-600'} mb-1">
                        ${transaction.type === 'credit' ? '+' : '-'}${this.currencySymbol}${parseFloat(transaction.amount).toFixed(2)}
                    </p>
                    <p class="text-sm text-gray-500">Balance: ${this.currencySymbol}${parseFloat(transaction.balance_after).toFixed(2)}</p>
                </div>
            </div>
        `).join('');

        container.innerHTML = transactionsHtml;
    }

    updateWalletBalance(balance) {
        const walletBalanceEl = document.getElementById('walletBalance');
        const numericBalance = parseFloat(balance);

        if (!isNaN(numericBalance)) {
            walletBalanceEl.textContent = `${this.currencySymbol}${numericBalance.toFixed(2)}`;
        } else {
            walletBalanceEl.textContent = `${this.currencySymbol}0.00`;
            console.warn('Invalid balance received:', balance);
        }
    }

    showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toast.className = `fixed bottom-6 right-6 z-[9999] px-6 py-4 rounded-2xl shadow-lg transform transition-all duration-300 ease-in-out ${
            isError ? 'bg-red-500' : 'bg-green-500'
        } text-white`;
        
        toastMessage.textContent = message;
        
        // Show toast
        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-x-full');
            toast.classList.add('opacity-100', 'translate-x-0');
        }, 100);
        
        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-full');
            toast.classList.remove('opacity-100', 'translate-x-0');
        }, 3000);
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