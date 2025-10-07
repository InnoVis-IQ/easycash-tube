<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Merge bonus wallet balances into main wallet
        $bonusWallets = DB::table('user_wallets')
            ->where('wallet_type', 'bonus')
            ->get();
        
        foreach ($bonusWallets as $bonusWallet) {
            // Get or find main wallet for this user
            $mainWallet = DB::table('user_wallets')
                ->where('user_id', $bonusWallet->user_id)
                ->where('wallet_type', 'main')
                ->first();
            
            if ($mainWallet) {
                // Add bonus wallet balance to main wallet
                DB::table('user_wallets')
                    ->where('id', $mainWallet->id)
                    ->increment('balance', $bonusWallet->balance);
                
                // Add bonus wallet earned to main wallet
                DB::table('user_wallets')
                    ->where('id', $mainWallet->id)
                    ->increment('total_earned', $bonusWallet->total_earned);
            }
        }
        
        // Delete all bonus wallets
        DB::table('user_wallets')
            ->where('wallet_type', 'bonus')
            ->delete();
        
        // Update main wallet settings: only main wallet can buy plans
        DB::table('user_wallets')
            ->where('wallet_type', 'main')
            ->update([
                'can_buy_plans' => true,
                'is_withdrawable' => false,
                'description' => 'Main wallet for deposits and bonuses'
            ]);
        
        // Update earn wallet settings: only earn wallet is withdrawable
        DB::table('user_wallets')
            ->where('wallet_type', 'earn')
            ->update([
                'can_buy_plans' => false,
                'is_withdrawable' => true,
                'description' => 'Earnings wallet for daily plan profits (withdrawable)'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be fully reversed
        // You would need to restore from backup if needed
    }
};

