<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_type',
        'balance',
        'total_deposited',
        'total_withdrawn',
        'total_earned',
        'is_withdrawable',
        'can_buy_plans',
        'description'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_deposited' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'is_withdrawable' => 'boolean',
        'can_buy_plans' => 'boolean'
    ];

    /**
     * Get the user that owns the wallet
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get wallet by type for a user
     */
    public static function getWalletByType($userId, $walletType)
    {
        return self::where('user_id', $userId)
                   ->where('wallet_type', $walletType)
                   ->first();
    }

    /**
     * Create or get wallet for user
     */
    public static function getOrCreateWallet($userId, $walletType)
    {
        $wallet = self::getWalletByType($userId, $walletType);
        
        if (!$wallet) {
            $wallet = self::create([
                'user_id' => $userId,
                'wallet_type' => $walletType,
                'balance' => 0.00,
                'total_deposited' => 0.00,
                'total_withdrawn' => 0.00,
                'total_earned' => 0.00,
                'is_withdrawable' => $walletType === 'earn', // Only earn wallet is withdrawable
                'can_buy_plans' => $walletType === 'main', // Only main wallet can buy plans
                'description' => self::getWalletDescription($walletType)
            ]);
        }
        
        return $wallet;
    }

    /**
     * Get wallet description based on type
     */
    public static function getWalletDescription($walletType)
    {
        $descriptions = [
            'main' => 'Main wallet for deposits and bonuses',
            'earn' => 'Earnings wallet for daily plan profits (withdrawable)'
        ];
        
        return $descriptions[$walletType] ?? '';
    }

    /**
     * Add funds to wallet
     */
    public function addFunds($amount, $type = 'deposit')
    {
        DB::beginTransaction();
        
        try {
            $this->increment('balance', $amount);
            
            if ($type === 'deposit') {
                $this->increment('total_deposited', $amount);
            } elseif ($type === 'earned') {
                $this->increment('total_earned', $amount);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Deduct funds from wallet
     */
    public function deductFunds($amount, $type = 'withdrawal')
    {
        if ($this->balance < $amount) {
            return false; // Insufficient funds
        }
        
        DB::beginTransaction();
        
        try {
            $this->decrement('balance', $amount);
            
            if ($type === 'withdrawal') {
                $this->increment('total_withdrawn', $amount);
            } elseif ($type === 'plan_purchase') {
                $this->increment('total_withdrawn', $amount);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Transfer funds between wallets
     */
    public function transferTo($targetWallet, $amount)
    {
        if ($this->balance < $amount) {
            return false; // Insufficient funds
        }
        
        DB::beginTransaction();
        
        try {
            $this->decrement('balance', $amount);
            $targetWallet->increment('balance', $amount);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get total balance across all wallets for user
     */
    public static function getTotalBalance($userId)
    {
        return self::where('user_id', $userId)->sum('balance');
    }

    /**
     * Get withdrawable balance (earn wallet only)
     */
    public static function getWithdrawableBalance($userId)
    {
        return self::where('user_id', $userId)
                   ->where('is_withdrawable', true)
                   ->sum('balance');
    }

    /**
     * Get plan purchase balance (all wallets)
     */
    public static function getPlanPurchaseBalance($userId)
    {
        return self::where('user_id', $userId)
                   ->where('can_buy_plans', true)
                   ->sum('balance');
    }

    /**
     * Scope for withdrawable wallets
     */
    public function scopeWithdrawable($query)
    {
        return $query->where('is_withdrawable', true);
    }

    /**
     * Scope for plan purchase wallets
     */
    public function scopeForPlanPurchase($query)
    {
        return $query->where('can_buy_plans', true);
    }

    /**
     * Scope for specific wallet type
     */
    public function scopeOfType($query, $walletType)
    {
        return $query->where('wallet_type', $walletType);
    }
}
