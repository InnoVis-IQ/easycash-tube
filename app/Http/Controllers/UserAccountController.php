<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use App\Http\Requests\StoreUserAccountRequest;
use App\Http\Requests\UpdateUserAccountRequest;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserAccountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAccount $userAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAccount $userAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserAccountRequest $request, UserAccount $userAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAccount $userAccount)
    {
        //
    }

    /**
     * Display user's wallet and earnings page
     */
    public function wallet()
    {
        $userId = Auth::user()->id;
        
        // Get user's subscriptions with earnings
        $subscriptions = \App\Models\UserSubscription::where('user_id', $userId)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate total earnings and withdrawals
        $totalEarnings = $subscriptions->sum('earning');
        $totalWithdrawals = \App\Models\Withdraw::where('user_id', $userId)
            ->where('status', 'transferred')
            ->sum('amount');
        $pendingWithdrawals = \App\Models\Withdraw::where('user_id', $userId)
            ->where('status', 'requested')
            ->sum('amount');
        
        // Get wallet balance
        $walletBalance = optional(\App\Models\UserAccount::where('user_id', $userId)->first())->net_amount ?? 0;
        
        // Get daily earnings history (last 30 days)
        $dailyEarnings = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dailyEarnings[$date] = 0;
        }
        
        // Calculate daily earnings based on subscription updates
        foreach ($subscriptions as $subscription) {
            if ($subscription->updated_at) {
                $updateDate = $subscription->updated_at->format('Y-m-d');
                if (isset($dailyEarnings[$updateDate])) {
                    $dailyEarnings[$updateDate] += $subscription->plan ? $subscription->plan->daily_profit : 0;
                }
            }
        }
        
        // Get withdrawal history
        $withdrawals = \App\Models\Withdraw::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get deposit history
        $deposits = \App\Models\Deposit::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate bonus (referral bonus if applicable)
        $bonusEarnings = 0;
        $user = \App\Models\User::find($userId);
        if ($user && $user->bonus_given) {
            $bonusEarnings = $user->bonus_given;
        }
        
        return view('backend.user.wallet.index', compact(
            'subscriptions',
            'totalEarnings',
            'totalWithdrawals',
            'pendingWithdrawals',
            'walletBalance',
            'dailyEarnings',
            'withdrawals',
            'deposits',
            'bonusEarnings'
        ));
    }
}
