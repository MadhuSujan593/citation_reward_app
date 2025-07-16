@extends('layouts.dashboard')

@section('title', 'My Wallet')

@section('content')
<div class="space-y-6 mt-8">
    <!-- Page Header -->
   

    <!-- Wallet Balance Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Balance Card -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Current Balance</h2>
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="text-4xl font-bold text-gray-800 mb-2" id="walletBalance">
                        {{ $wallet->currency_symbol }}{{ number_format($wallet->balance, 2) }}
                    </div>
                    <p class="text-gray-600">Available funds for research rewards</p>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-3">
                    <button 
                        id="addFundsBtn"
                        onclick="openAddFundsModal()"
                        class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl shadow-md hover:bg-emerald-700 hover:shadow-lg transition-all duration-200 transform hover:scale-105 border-0"
                        style="display: inline-flex !important; background-color: #059669 !important; color: white !important; min-height: 44px; min-width: 120px;"
                    >
                        <i class="fas fa-plus text-sm" style="color: white !important;"></i>
                        <span style="color: white !important;">Add Funds</span>
                    </button>
                    <button 
                        onclick="refreshWallet()"
                        class="inline-flex items-center space-x-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-all duration-200 border-0"
                        style="display: inline-flex !important; min-height: 44px; min-width: 100px;"
                    >
                        <i class="fas fa-sync-alt text-sm"></i>
                        <span>Refresh</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="space-y-4">
            <!-- Total Credited -->
            <div class="bg-white/80 backdrop-blur-xl rounded-xl p-4 shadow-lg border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Credited</p>
                        <p class="text-xl font-bold text-green-600">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_credited'], 2) }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Debited -->
            <div class="bg-white/80 backdrop-blur-xl rounded-xl p-4 shadow-lg border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Debited</p>
                        <p class="text-xl font-bold text-red-600">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_debited'], 2) }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-down text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Transaction Count -->
            <div class="bg-white/80 backdrop-blur-xl rounded-xl p-4 shadow-lg border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Transactions</p>
                        <p class="text-xl font-bold text-indigo-600">{{ $stats['transaction_count'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-indigo-600"></i>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white/80 backdrop-blur-xl rounded-xl p-4 shadow-lg border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-xl font-bold text-purple-600">
                            {{ is_numeric($stats['this_month']) ? $wallet->currency_symbol . number_format($stats['this_month'], 2) : $stats['this_month'] }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
            <button 
                onclick="loadAllTransactions()"
                class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors"
            >
                View All
            </button>
        </div>

        <div id="transactionsContainer">
            @if($transactions->count() > 0)
                <div class="space-y-4">
                    @foreach($transactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 {{ $transaction->type === 'credit' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                                    <i class="{{ $transaction->type === 'credit' ? 'fas fa-arrow-up text-green-600' : 'fas fa-arrow-down text-red-600' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $transaction->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->formatted_amount }}
                                </p>
                                <p class="text-sm text-gray-500">Balance: {{ $transaction->formatted_balance_after }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">No transactions yet</p>
                    <p class="text-sm text-gray-400">Add funds to get started</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div id="addFundsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="addFundsModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Add Funds</h3>
                <button onclick="closeAddFundsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="addFundsForm" class="space-y-4">
                @csrf
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount ({{ $wallet->currency }})
                    </label>
                    <input 
                        type="number" 
                        id="amount" 
                        name="amount" 
                        min="1" 
                        max="10000" 
                        step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter amount"
                        required
                    >
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                        placeholder="e.g., Research funding for Q1 2024"
                    ></textarea>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button 
                        type="button"
                        onclick="closeAddFundsModal()"
                        class="flex-1 px-4 py-3 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
        type="submit"
        class="flex-1 px-4 py-3 text-white rounded-xl hover:shadow-lg transition-all duration-200 transform hover:scale-105"
        style="
            background: linear-gradient(to right, #10b981, #0d9488) !important;
            color: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 44px !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 10 !important;
        "
    >
        <span style="color: white !important;">Add Funds</span>
    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- All Transactions Modal -->
<div id="allTransactionsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[80vh] transform transition-all duration-300 scale-95 opacity-0" id="allTransactionsModalContent">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">All Transactions</h3>
                <button onclick="closeAllTransactionsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="allTransactionsContainer" class="max-h-96 overflow-y-auto">
                <!-- Transactions will be loaded here -->
            </div>
        </div>
    </div>
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
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
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">No transactions found</p>
                </div>
            `;
            return;
        }

        const transactionsHtml = transactions.map(transaction => `
            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl hover:bg-gray-50 transition-colors mb-3">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 ${transaction.type === 'credit' ? 'bg-green-100' : 'bg-red-100'} rounded-lg flex items-center justify-center">
                        <i class="${transaction.type === 'credit' ? 'fas fa-arrow-up text-green-600' : 'fas fa-arrow-down text-red-600'}"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">${transaction.description}</p>
                        <p class="text-sm text-gray-500">${new Date(transaction.created_at).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'short', 
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold ${transaction.type === 'credit' ? 'text-green-600' : 'text-red-600'}">
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
        walletBalanceEl.textContent = `${this.currencySymbol}0.00`; // fallback
        console.warn('Invalid balance received:', balance);
        debugger;
    }
    }

    showToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toast.className = `fixed bottom-6 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${
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

    // Use requestAnimationFrame for smoother and guaranteed rendering
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