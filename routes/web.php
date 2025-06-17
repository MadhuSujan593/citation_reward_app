<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublishPaperController;

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
    Route::get('/dashboard/switch/{type}', [DashboardController::class, 'switchView'])->name('dashboard.switch');
    Route::post('/profile-update', [ProfileController::class, 'update'])->name('profile.edit');
    Route::delete('/profile-delete', [AuthController::class, 'deleteUserAccount'])->name('profile.del');

    //upload papers routes
    Route::post('/papers/upload', [PublishPaperController::class, 'store'])->name('papers.upload');
    Route::put('/papers/{paper}', [PublishPaperController::class, 'update'])->name('papers.update');
    Route::get('/dashboard/papers', [DashboardController::class, 'showPapers'])->name('dashboard.papers');
    Route::delete('/papers/{paper}', [PublishPaperController::class, 'destroy'])->name('papers.destroy');
    
    Route::get('/papers/search', [PublishPaperController::class, 'search'])->name('papers.search');
});
