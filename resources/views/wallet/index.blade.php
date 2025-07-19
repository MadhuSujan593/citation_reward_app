@extends('layouts.dashboard')

@section('title', 'My Wallet')

@section('content')
<!-- Add top padding for mobile header -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-3 sm:p-6 pt-20 md:pt-6">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        <!-- Header - Hidden on mobile since mobile-header shows instead -->
        <div class="hidden md:flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                    My Wallet
                </h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Manage your research funding</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-sm sm:text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Mobile Title - Show only on mobile -->
        <div class="md:hidden text-center mb-6">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                My Wallet
            </h1>
            <p class="text-gray-600 mt-1 text-sm">Manage your research funding</p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Balance Card - Takes up 2 columns on large screens, centered on mobile -->
            <div class="lg:col-span-2 lg:col-start-1">
                <div class="group relative bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 overflow-hidden mx-auto max-w-md lg:max-w-none">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-tr from-emerald-500/10 to-teal-500/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                    <!-- Absolutely guaranteed visible content -->
                                    <div class="text-white text-center flex items-center justify-center w-full h-full">
                                        <!-- Simple text that MUST be visible -->
                                        <span class="text-xl sm:text-2xl font-bold" style="line-height: 1; color: white !important; display: block !important;">💰</span>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">Available Balance</h2>
                                    <p class="text-xs sm:text-sm text-gray-500">Ready for research rewards</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6 sm:mb-8 text-center lg:text-left">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2 break-all" id="walletBalance">
                                {{ $wallet->currency_symbol }}{{ number_format($wallet->balance, 2) }}
                            </div>
                            <div class="w-16 sm:w-24 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full mx-auto lg:mx-0"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                            <button 
                                id="addFundsBtn"
                                onclick="openAddFundsModal()"
                                class="group flex-1 relative overflow-hidden bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                            >
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                    <span>Add Funds</span>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                            
                            <button 
                                onclick="refreshWallet()"
                                class="group relative overflow-hidden bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 sm:px-6 py-3 sm:py-4 rounded-2xl font-semibold transition-all duration-300 transform hover:-translate-y-1"
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

            <!-- Stats Grid - Takes up 2 columns on large screens -->
            <div class="lg:col-span-2 grid grid-cols-2 gap-3 sm:gap-6">
                <!-- Total Credited -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4 gap-2">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fa-solid fa-arrow-trend-up text-white text-sm"></i>
                        </div>
                        <div class="text-indigo-500 text-xs sm:text-sm font-medium bg-indigo-50 px-2 sm:px-3 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-up mr-1"></i>
                            Credit
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Credited</p>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-600 break-all">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_credited'], 2) }}
                        </p>
                    </div>
                </div>

                <!-- Total Debited -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4 gap-2">
                        <!-- ICON IN COLORED SQUARE -->
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-slate-400 to-gray-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0" id="debit-icon-container">
                            <i class="fas fa-arrow-down text-white text-sm" id="debit-icon"></i>
                        </div>
                        <div class="text-red-600 text-xs sm:text-sm font-medium bg-red-50 px-2 sm:px-3 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-down mr-1"></i>
                            Debit
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Debited</p>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-700 break-all">
                            {{ $wallet->currency_symbol }}{{ number_format($stats['total_debited'], 2) }}
                        </p>
                    </div>
                </div>

                <!-- Transaction Count -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4 gap-2">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fa-solid fa-arrow-right-arrow-left text-white text-sm"></i>
                        </div>
                        <div class="text-blue-500 text-xs sm:text-sm font-medium bg-blue-50 px-2 sm:px-3 py-1 rounded-full">
                            Total
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">Transactions</p>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600">{{ $stats['transaction_count'] }}</p>
                    </div>
                </div>

                <!-- This Month -->
                <div class="group bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4 gap-2">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fa-solid fa-calendar-days text-white text-sm"></i>
                        </div>
                        <div class="text-purple-500 text-xs sm:text-sm font-medium bg-purple-50 px-2 sm:px-3 py-1 rounded-full">
                            Monthly
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs sm:text-sm mb-1">This Month</p>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600 break-all">
                            {{ is_numeric($stats['this_month']) ? $wallet->currency_symbol . number_format($stats['this_month'], 2) : $stats['this_month'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-white/20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8 gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-history text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Recent Transactions</h2>
                        <p class="text-gray-600 text-xs sm:text-sm">Your latest financial activities</p>
                    </div>
                </div>
                <button 
                    onclick="loadAllTransactions()"
                    class="group flex items-center justify-center space-x-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto"
                >
                    <span>View All</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </button>
            </div>

            <div id="transactionsContainer">
                @if($transactions->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($transactions as $transaction)
                            <div class="group flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 bg-gradient-to-r from-gray-50/80 to-white/80 backdrop-blur-sm rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-100/50 gap-3 sm:gap-0">
                                <div class="flex items-center space-x-3 sm:space-x-4">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 {{ $transaction->type === 'credit' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-rose-600' }} rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                        <i class="{{ $transaction->type === 'credit' ? 'fas fa-plus text-white' : 'fas fa-minus text-white' }}"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-gray-800 mb-1 text-sm sm:text-base truncate">{{ $transaction->description }}</p>
                                        <p class="text-xs sm:text-sm text-gray-500 flex items-center">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            {{ $transaction->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-xl sm:text-2xl font-bold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }} mb-1 break-all">
                                        {{ $transaction->formatted_amount }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-500 break-all">
                                        Balance: {{ $transaction->formatted_balance_after }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 sm:py-16">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <i class="fas fa-receipt text-gray-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">No transactions yet</h3>
                        <p class="text-gray-500 text-sm sm:text-base px-4">Add funds to get started with your research funding</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div id="addFundsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="addFundsModalContent">
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
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
                        Amount ({{ $wallet->currency }})
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-sm sm:text-base">{{ $wallet->currency_symbol }}</span>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            min="1" 
                            max="10000" 
                            step="0.01"
                            class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-gray-50/50 text-sm sm:text-base"
                            placeholder="0.00"
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
                        class="flex-1 px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 text-sm sm:text-base"
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

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 pointer-events-none translate-x-full text-white">
    <span id="toastMessage" class="font-medium text-sm"></span>
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
            <div class="group bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-10 h-10 ${transaction.type === 'credit' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-rose-600'} rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                            <i class="${transaction.type === 'credit' ? 'fas fa-plus text-white text-xs' : 'fas fa-minus text-white text-xs'}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-gray-900 text-sm mb-0.5 truncate">${transaction.description}</h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1" style="font-size: 10px;"></i>
                                <span>${new Date(transaction.created_at).toLocaleDateString('en-US', { 
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end ml-3">
                        <div class="text-base font-bold ${transaction.type === 'credit' ? 'text-green-600' : 'text-red-600'}">
                            ${transaction.type === 'credit' ? '+' : '-'}${this.currencySymbol}${parseFloat(transaction.amount).toFixed(2)}
                        </div>
                        <div class="text-xs text-gray-500">
                            ${this.currencySymbol}${parseFloat(transaction.balance_after).toFixed(2)}
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
            walletBalanceEl.textContent = `${this.currencySymbol}${numericBalance.toFixed(2)}`;
        } else {
            walletBalanceEl.textContent = `${this.currencySymbol}0.00`;
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
                // Override loadPapers method temporarily to prevent loading papers
                const originalLoadPapers = Dashboard.prototype.loadPapers;
                Dashboard.prototype.loadPapers = function() {
                    console.log('Skipping loadPapers on wallet page');
                    // Do nothing - skip loading papers
                };
                
                // Create Dashboard instance (now won't load papers)
                window.dashboard = new Dashboard();
                
                // Restore original loadPapers method for other instances
                Dashboard.prototype.loadPapers = originalLoadPapers;
                
                                                                  // Use the original Dashboard showToast method for consistency
                
                console.log('Dashboard initialized for wallet page (no papers loaded)');
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