<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserWallet;
use App\Models\UserAccount;
use App\Models\UserSubscription;
use App\Models\InvestmentPlan;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display user's wallet dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get or create all wallets for user
        $mainWallet = UserWallet::getOrCreateWallet($userId, 'main');
        $earnWallet = UserWallet::getOrCreateWallet($userId, 'earn');
        
        // Get wallet statistics
        $totalBalance = UserWallet::getTotalBalance($userId);
        $withdrawableBalance = UserWallet::getWithdrawableBalance($userId);
        $planPurchaseBalance = UserWallet::getPlanPurchaseBalance($userId);
        
        // Get recent transactions (from user account for now)
        $recentTransactions = UserAccount::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        // Get user's active subscriptions
        $activeSubscriptions = UserSubscription::where('user_id', $userId)
            ->where('expire_at', '>', date('Y-m-d'))
            ->with('plan')
            ->get();
        
        return view('backend.user.wallet.index', compact(
            'mainWallet',
            'earnWallet',
            'totalBalance',
            'withdrawableBalance',
            'planPurchaseBalance',
            'recentTransactions',
            'activeSubscriptions'
        ));
    }

    /**
     * Transfer funds between wallets
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'from_wallet' => 'required|in:main',
            'to_wallet' => 'required|in:earn',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $userId = Auth::id();
        $amount = $request->amount;
        
        // Get source and destination wallets
        $fromWallet = UserWallet::getWalletByType($userId, $request->from_wallet);
        $toWallet = UserWallet::getWalletByType($userId, $request->to_wallet);
        
        if (!$fromWallet || !$toWallet) {
            return back()->with('error', 'Wallet not found.');
        }
        
        if ($fromWallet->balance < $amount) {
            return back()->with('error', 'Insufficient balance in source wallet.');
        }
        
        // Perform transfer
        if ($fromWallet->transferTo($toWallet, $amount)) {
            return back()->with('success', 'Funds transferred successfully.');
        } else {
            return back()->with('error', 'Transfer failed. Please try again.');
        }
    }

    /**
     * Get wallet balance (API endpoint)
     */
    public function getBalance(Request $request)
    {
        $userId = Auth::id();
        $walletType = $request->get('wallet_type', 'all');
        
        if ($walletType === 'all') {
            $balance = UserWallet::getTotalBalance($userId);
            $withdrawable = UserWallet::getWithdrawableBalance($userId);
            $forPlans = UserWallet::getPlanPurchaseBalance($userId);
            
            return response()->json([
                'total_balance' => $balance,
                'withdrawable_balance' => $withdrawable,
                'plan_purchase_balance' => $forPlans
            ]);
        } else {
            $wallet = UserWallet::getWalletByType($userId, $walletType);
            return response()->json([
                'balance' => $wallet ? $wallet->balance : 0
            ]);
        }
    }

    /**
     * Get wallet history
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        $walletType = $request->get('wallet_type', 'all');
        
        // Get user's wallets
        $wallets = UserWallet::where('user_id', $userId);
        
        if ($walletType !== 'all') {
            $wallets->where('wallet_type', $walletType);
        }
        
        $wallets = $wallets->get();
        
        // Get recent deposits and withdrawals
        $deposits = \App\Models\Deposit::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        $withdrawals = \App\Models\Withdraw::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        return view('backend.user.wallet.history', compact(
            'wallets',
            'deposits',
            'withdrawals',
            'walletType'
        ));
    }

    /**
     * Admin wallet management
     */
    public function adminIndex(Request $request)
    {
        $query = UserWallet::with('user');
        
        // Filter by wallet type
        if ($request->has('wallet_type') && $request->wallet_type) {
            $query->where('wallet_type', $request->wallet_type);
        }
        
        // Search by user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $wallets = $query->orderBy('updated_at', 'desc')->paginate(20);
        
        // Get wallet statistics
        $stats = [
            'total_wallets' => UserWallet::count(),
            'total_balance' => UserWallet::sum('balance'),
            'main_wallets' => UserWallet::where('wallet_type', 'main')->count(),
            'earn_wallets' => UserWallet::where('wallet_type', 'earn')->count(),
        ];
        
        return view('backend.admin.wallet.index', compact(
            'wallets',
            'stats',
            'request'
        ));
    }

    /**
     * Adjust user wallet balance (Admin only)
     */
    public function adjustBalance(Request $request, UserWallet $wallet)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:add,deduct',
            'reason' => 'required|string|max:255'
        ]);
        
        $amount = $request->amount;
        $type = $request->type;
        
        if ($type === 'add') {
            $wallet->addFunds($amount, 'admin_adjustment');
        } else {
            if ($wallet->balance < $amount) {
                return back()->with('error', 'Insufficient balance to deduct.');
            }
            $wallet->deductFunds($amount, 'admin_adjustment');
        }
        
        return back()->with('success', 'Wallet balance adjusted successfully.');
    }

    /**
     * Get wallet statistics for dashboard
     */
    public function getStats()
    {
        $userId = Auth::id();
        
        $stats = [
            'main_balance' => UserWallet::getWalletByType($userId, 'main')->balance ?? 0,
            'earn_balance' => UserWallet::getWalletByType($userId, 'earn')->balance ?? 0,
            'total_balance' => UserWallet::getTotalBalance($userId),
            'withdrawable_balance' => UserWallet::getWithdrawableBalance($userId),
            'plan_purchase_balance' => UserWallet::getPlanPurchaseBalance($userId)
        ];
        
        return response()->json($stats);
    }
}
