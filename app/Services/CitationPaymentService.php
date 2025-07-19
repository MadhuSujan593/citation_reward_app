<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\PaperCitation;
use App\Models\PublishedPaper;
use Illuminate\Support\Facades\DB;

class CitationPaymentService
{
    const CITATION_COST = 100.00; // 100 rupees per citation

    /**
     * Process payment for citing a paper
     */
    public static function processCitationPayment($userId, $paperId)
    {
        try {
            DB::beginTransaction();

            // Get paper and its funder
            $paper = PublishedPaper::find($paperId);
            if (!$paper) {
                throw new \Exception('Paper not found');
            }

            $funder = $paper->user;
            if (!$funder) {
                throw new \Exception('Paper funder not found');
            }

            // Ensure funder has a wallet
            $funderWallet = $funder->wallet;
            if (!$funderWallet) {
                $funderWallet = Wallet::create([
                    'user_id' => $funder->id,
                    'balance' => 0.00,
                    'currency' => 'INR',
                    'is_active' => true
                ]);
            }

            // Check if funder has sufficient funds
            if ($funderWallet->balance < self::CITATION_COST) {
                throw new \Exception('Paper funder has insufficient funds. Citation cannot be processed.');
            }

            // Get admin wallet
            $adminWallet = AdminWalletService::getAdminWallet();

            $paperTitle = substr($paper->title, 0, 50) . '...';

            // Deduct from funder wallet
            $funderWallet->deductFunds(
                self::CITATION_COST,
                'Citation fee for paper: ' . $paperTitle,
                $paperId,
                'paper_citation'
            );

            // Add to admin wallet
            $adminWallet->addFunds(
                self::CITATION_COST,
                'Citation fee received for: ' . $paperTitle,
                $paperId,
                'paper_citation'
            );

            DB::commit();
            return ['success' => true, 'message' => 'Citation processed successfully'];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Process refund for unciting a paper
     */
    public static function processCitationRefund($userId, $paperId)
    {
        try {
            DB::beginTransaction();

            // Get paper and its funder
            $paper = PublishedPaper::find($paperId);
            if (!$paper) {
                throw new \Exception('Paper not found');
            }

            $funder = $paper->user;
            if (!$funder) {
                throw new \Exception('Paper funder not found');
            }

            // Ensure funder has a wallet
            $funderWallet = $funder->wallet;
            if (!$funderWallet) {
                $funderWallet = Wallet::create([
                    'user_id' => $funder->id,
                    'balance' => 0.00,
                    'currency' => 'INR',
                    'is_active' => true
                ]);
            }

            $adminWallet = AdminWalletService::getAdminWallet();

            // Check if admin has sufficient funds for refund
            if ($adminWallet->balance < self::CITATION_COST) {
                throw new \Exception('Admin wallet has insufficient funds for refund');
            }

            $paperTitle = substr($paper->title, 0, 50) . '...';

            // Deduct from admin wallet
            $adminWallet->deductFunds(
                self::CITATION_COST,
                'Citation refund for: ' . $paperTitle,
                $paperId,
                'paper_uncitation'
            );

            // Add to funder wallet
            $funderWallet->addFunds(
                self::CITATION_COST,
                'Citation refund for: ' . $paperTitle,
                $paperId,
                'paper_uncitation'
            );

            DB::commit();
            return ['success' => true, 'message' => 'Refund processed successfully'];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Check if paper funder has sufficient funds for citation
     */
    public static function canProcessCitation($paperId)
    {
        $paper = PublishedPaper::find($paperId);
        if (!$paper || !$paper->user || !$paper->user->wallet) {
            return false;
        }

        return $paper->user->wallet->balance >= self::CITATION_COST;
    }
} 