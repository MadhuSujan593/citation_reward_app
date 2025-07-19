<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClaimRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublishPaperController;
use App\Http\Controllers\WalletController;

// Guest-only routes
Route::middleware('guest')->group(function () {
    // Welcome
    Route::get('/', function () {
        return view('auth.login');
    });

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register-post', [AuthController::class, 'register'])->name('register.post');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login-post', [AuthController::class, 'login'])->name('login.post');

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});

// Authenticated-only routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-new', function () {
        return view('dashboard-new');
    })->name('dashboard.new');
    Route::get('/dashboard/switch/{type}', [DashboardController::class, 'switchView'])->name('dashboard.switch');
    Route::post('/profile-update', [ProfileController::class, 'update'])->name('profile.edit');
    Route::delete('/profile-delete', [AuthController::class, 'deleteUserAccount'])->name('profile.del');
    Route::post('/dashboard/switch-role', [DashboardController::class, 'setRole'])->name('dashboard.setRole');

    //upload papers routes
    Route::post('/papers/upload', [PublishPaperController::class, 'store'])->name('papers.upload');
    Route::put('/papers/{paper}', [PublishPaperController::class, 'update'])->name('papers.update');
    Route::get('/dashboard/papers', [DashboardController::class, 'showPapers'])->name('dashboard.papers');
    Route::delete('/papers/{paper}', [PublishPaperController::class, 'destroy'])->name('papers.destroy');
    
    Route::get('/papers/search', [PublishPaperController::class, 'search'])->name('papers.search');
    Route::post('/cite-paper/{publishedPaper}', [PublishPaperController::class, 'cite'])->name('papers.cite');
    Route::post('/uncite-paper/{publishedPaper}', [PublishPaperController::class, 'unCite'])->name('papers.cite');
    Route::get('/my-citations', [PublishPaperController::class, 'myCitations']);
    
    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/add-funds', [WalletController::class, 'addFunds'])->name('wallet.add-funds');
    Route::get('/wallet/transactions', [WalletController::class, 'getTransactions'])->name('wallet.transactions');
    
    // Claim Request routes
    Route::get('/claim-requests', [ClaimRequestController::class, 'index'])->name('claim-requests.index');
    Route::post('/claim-requests', [ClaimRequestController::class, 'store'])->name('claim-requests.store');
    
    // Admin routes
    Route::middleware('admin')->group(function() {
        Route::get('/admin/claim-requests', [ClaimRequestController::class, 'adminIndex'])->name('admin.claim-requests');
        Route::post('/admin/claim-requests/{claimRequest}/approve', [ClaimRequestController::class, 'approve'])->name('admin.claim-requests.approve');
        Route::post('/admin/claim-requests/{claimRequest}/reject', [ClaimRequestController::class, 'reject'])->name('admin.claim-requests.reject');
    });
    Route::get('/wallet/stats', [WalletController::class, 'getWalletStats'])->name('wallet.stats');
});
