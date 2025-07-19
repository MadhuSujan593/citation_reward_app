<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'is_active'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected $appends = ['formatted_balance', 'currency_symbol'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getFormattedBalanceAttribute()
    {
        return '$' . number_format($this->balance, 2);
    }

    public function getCurrencySymbolAttribute()
    {
        return match (strtoupper($this->currency)) {
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            default => '$',
        };
    }

    public function addFunds($amount, $description = 'Funds added', $referenceId = null, $referenceType = null)
    {
        $this->balance += $amount;
        $this->save();

        // Create transaction record
        $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description,
            'balance_after' => $this->balance,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType
        ]);

        return $this;
    }

    public function deductFunds($amount, $description = 'Funds deducted', $referenceId = null, $referenceType = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient funds');
        }

        $this->balance -= $amount;
        $this->save();

        // Create transaction record
        $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
            'balance_after' => $this->balance,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType
        ]);

        return $this;
    }
} 