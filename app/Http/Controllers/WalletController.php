<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a Funder
        if ($user->role !== 'Funder') {
            abort(403, 'Access denied. Wallet is only available for Funder role.');
        }
        
        // Ensure user has a wallet
        $wallet = $user->wallet;
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'currency' => 'INR',
                'is_active' => true
            ]);
        }

        // Get recent transactions (top 5 only)
        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get wallet statistics
        $stats = [
            'total_credited' => $wallet->transactions()->where('type', 'credit')->sum('amount'),
            'total_debited' => $wallet->transactions()->where('type', 'debit')->sum('amount'),
            'transaction_count' => $wallet->transactions()->count(),
            'this_month' => $wallet->transactions()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        $userRole = $user->role ?? 'Citer';

        return view('wallet.index', compact('wallet', 'transactions', 'stats', 'userRole'));
    }

    public function addFunds(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is a Funder
        if ($user->role !== 'Funder') {
            return response()->json(['success' => false, 'message' => 'Access denied. Wallet is only available for Funder role.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:10000',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $wallet = $user->wallet;

            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0.00,
                    'currency' => 'USD',
                    'is_active' => true
                ]);
            }

            $amount = $request->amount;
            $description = $request->description ?: 'Funds added to wallet';

            $wallet->addFunds($amount, $description);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Funds added successfully',
                'wallet' => [
                    'balance' => $wallet->formatted_balance,
                    'raw_balance' => $wallet->balance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add funds: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    public function getWalletStats()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $stats = [
            'balance' => $wallet->formatted_balance,
            'raw_balance' => $wallet->balance,
            'total_credited' => $wallet->transactions()->where('type', 'credit')->sum('amount'),
            'total_debited' => $wallet->transactions()->where('type', 'debit')->sum('amount'),
            'transaction_count' => $wallet->transactions()->count(),
            'this_month' => $wallet->transactions()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
