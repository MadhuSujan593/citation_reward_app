<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class AdminWalletService
{
    /**
     * Get or create admin user and wallet
     */
    public static function getAdminWallet()
    {
        // Find or create admin user
        $admin = User::where('email', 'admin@citationapp.com')->first();
        if (!$admin) {
            $admin = User::create([
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@citationapp.com',
                'password' => Hash::make('admin123'),
                'role' => 'Admin'
            ]);
        }

        // Get or create admin wallet
        $wallet = $admin->wallet;
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $admin->id,
                'balance' => 0.00,
                'currency' => 'INR',
                'is_active' => true
            ]);
        }

        return $wallet;
    }

    /**
     * Transfer funds from one wallet to another
     */
    public static function transferFunds($fromWallet, $toWallet, $amount, $description = 'Fund transfer')
    {
        // Deduct from source wallet
        $fromWallet->deductFunds($amount, $description);
        
        // Add to destination wallet
        $toWallet->addFunds($amount, $description);
        
        return true;
    }
} 