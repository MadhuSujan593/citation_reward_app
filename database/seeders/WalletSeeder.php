<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Create wallet for each user
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'currency' => 'INR',
                'is_active' => true
            ]);

            // Add some sample transactions for funders
            if ($user->role === 'Funder') {
                // Add initial funds
                $wallet->addFunds(1000.00, 'Initial research funding');
                
                // Add some more transactions
                $wallet->addFunds(500.00, 'Q1 2024 research grant');
                $wallet->addFunds(750.00, 'Collaboration project funding');
                
                // Add some deductions (simulating rewards given)
                $wallet->deductFunds(200.00, 'Citation reward payment');
                $wallet->deductFunds(150.00, 'Research collaboration reward');
            }
        }
    }
}
