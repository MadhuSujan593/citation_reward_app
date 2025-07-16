<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'description',
        'balance_after',
        'reference_id',
        'reference_type'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2'
    ];

    protected $appends = ['formatted_amount', 'formatted_balance_after'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === 'credit' ? '+' : '-';
        $symbol = $this->getCurrencySymbol($this->currency);
        return $symbol . number_format($this->amount, 2);
    }

    protected function getCurrencySymbol($currency)
    {
        return match (strtoupper($currency)) {
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
            default => '',
        };
    }

    public function getFormattedBalanceAfterAttribute()
    {
        $symbol = $this->getCurrencySymbol($this->wallet->currency ?? 'INR');
        
        return $symbol . number_format($this->balance_after, 2);
    }

    public function getTypeColorAttribute()
    {
        return $this->type === 'credit' ? 'text-green-600' : 'text-red-600';
    }

    public function getTypeIconAttribute()
    {
        return $this->type === 'credit' ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
    }
} 