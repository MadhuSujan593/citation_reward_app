<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if admin already exists
        $existingAdmin = User::where('email', 'admin@citationapp.com')->first();
        
        if ($existingAdmin) {
            $this->info('Admin user already exists!');
            $this->line('Email: admin@citationapp.com');
            $this->line('You can login with the existing admin account.');
            return;
        }

        // Create admin user
        $admin = User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@citationapp.com',
            'password' => Hash::make('admin123'),
            'role' => 'Citer'
        ]);

        // Create admin wallet with some initial funds
        $wallet = Wallet::create([
            'user_id' => $admin->id,
            'balance' => 10000.00, // ₹10,000 initial balance
            'currency' => 'INR',
            'is_active' => true
        ]);

        // Add initial transaction
        $wallet->addFunds(10000.00, 'Initial admin wallet setup', null, 'system_setup');

        $this->info('✅ Admin user created successfully!');
        $this->line('');
        $this->line('📧 Email: admin@citationapp.com');
        $this->line('🔑 Password: admin123');
        $this->line('💰 Initial Balance: ₹10,000');
        $this->line('');
        $this->line('You can now login with these credentials to access the admin dashboard.');
    }
}
