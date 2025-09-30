<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\UserWallet;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\UserNotification;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /**
     * Display comprehensive user management dashboard
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'USER');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereHas('subscriptions', function($q) {
                    $q->where('expire_at', '>', now());
                });
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('subscriptions', function($q) {
                    $q->where('expire_at', '>', now());
                });
            } elseif ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            }
        }

        // Filter by registration date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->with(['subscriptions.plan', 'deposits', 'withdrawals'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        // Get statistics
        $stats = [
            'total_users' => User::where('role', 'USER')->count(),
            'active_users' => User::where('role', 'USER')
                ->whereHas('subscriptions', function($q) {
                    $q->where('expire_at', '>', now());
                })->count(),
            'new_users_today' => User::where('role', 'USER')
                ->whereDate('created_at', today())->count(),
            'total_deposits' => Deposit::where('status', 'approved')->sum('amount'),
            'total_withdrawals' => Withdraw::where('status', 'transferred')->sum('amount'),
        ];

        return view('backend.admin.users.index', compact(
            'users', 
            'stats', 
            'request'
        ));
    }

    /**
     * Show detailed user information
     */
    public function show(User $user)
    {
        // Get user's comprehensive data
        $userData = [
            'profile' => $user,
            'wallets' => UserWallet::where('user_id', $user->id)->get(),
            'subscriptions' => UserSubscription::where('user_id', $user->id)
                ->with('plan')
                ->orderBy('created_at', 'desc')
                ->get(),
            'deposits' => Deposit::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get(),
            'withdrawals' => Withdraw::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get(),
            'notifications' => UserNotification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(),
        ];

        // Calculate user statistics
        $userStats = [
            'total_invested' => $userData['subscriptions']->sum(function($sub) {
                return $sub->plan ? $sub->plan->amount : 0;
            }),
            'total_earned' => $userData['subscriptions']->sum('earning'),
            'total_deposited' => $userData['deposits']->where('status', 'approved')->sum('amount'),
            'total_withdrawn' => $userData['withdrawals']->where('status', 'transferred')->sum('amount'),
            'referral_count' => User::where('referred_by', $user->referral_code)->count(),
            'referral_earnings' => User::where('referred_by', $user->referral_code)
                ->where('bonus_given', '>', 0)
                ->sum('bonus_given'),
        ];

        return view('backend.admin.users.show', compact('userData', 'userStats'));
    }

    /**
     * Block/Unblock user
     */
    public function toggleBlock(User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        
        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        
        // Send notification to user
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system_alert',
            'title' => $status === 'blocked' ? 'Account Blocked' : 'Account Unblocked',
            'message' => $status === 'blocked' 
                ? 'Your account has been blocked. Please contact support for assistance.'
                : 'Your account has been unblocked. You can now access all features.',
            'is_important' => true
        ]);

        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Send notification to user
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system_alert',
            'title' => 'Password Reset',
            'message' => 'Your password has been reset by an administrator. Please log in with your new password.',
            'is_important' => true
        ]);

        return back()->with('success', 'Password reset successfully.');
    }

    /**
     * Add funds to user account
     */
    public function addFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'wallet_type' => 'required|in:main,earn,bonus',
            'reason' => 'required|string|max:255'
        ]);

        $wallet = UserWallet::getOrCreateWallet($user->id, $request->wallet_type);
        $wallet->addFunds($request->amount, 'admin_adjustment');

        // Send notification to user
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system_alert',
            'title' => 'Funds Added',
            'message' => "Rs. {$request->amount} has been added to your " . ucfirst($request->wallet_type) . " wallet. Reason: {$request->reason}",
            'data' => [
                'amount' => $request->amount,
                'wallet_type' => $request->wallet_type,
                'reason' => $request->reason
            ],
            'is_important' => true
        ]);

        return back()->with('success', "Rs. {$request->amount} added to user's {$request->wallet_type} wallet.");
    }

    /**
     * Send notification to user
     */
    public function sendNotification(Request $request, User $user)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:plan_expiry,new_offer,deposit_approved,withdrawal_processed,referral_bonus,lucky_winner,system_alert',
            'is_important' => 'boolean'
        ]);

        UserNotification::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'is_important' => $request->boolean('is_important', false),
            'expires_at' => now()->addDays(30)
        ]);

        return back()->with('success', 'Notification sent to user successfully.');
    }

    /**
     * Get user activity log
     */
    public function getActivityLog(User $user)
    {
        $activities = [];

        // Add subscription activities
        foreach ($user->subscriptions as $subscription) {
            $activities[] = [
                'type' => 'subscription',
                'description' => "Purchased {$subscription->plan->title}",
                'amount' => $subscription->plan->amount,
                'date' => $subscription->created_at,
                'status' => $subscription->expire_at > now() ? 'active' : 'expired'
            ];
        }

        // Add deposit activities
        foreach ($user->deposits as $deposit) {
            $activities[] = [
                'type' => 'deposit',
                'description' => "Deposited Rs. {$deposit->amount}",
                'amount' => $deposit->amount,
                'date' => $deposit->created_at,
                'status' => $deposit->status
            ];
        }

        // Add withdrawal activities
        foreach ($user->withdrawals as $withdrawal) {
            $activities[] = [
                'type' => 'withdrawal',
                'description' => "Withdrew Rs. {$withdrawal->amount}",
                'amount' => $withdrawal->amount,
                'date' => $withdrawal->created_at,
                'status' => $withdrawal->status
            ];
        }

        // Sort by date
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return response()->json($activities);
    }

    /**
     * Export user data
     */
    public function exportUserData(User $user)
    {
        $userData = [
            'profile' => $user->toArray(),
            'wallets' => UserWallet::where('user_id', $user->id)->get()->toArray(),
            'subscriptions' => UserSubscription::where('user_id', $user->id)
                ->with('plan')
                ->get()
                ->toArray(),
            'deposits' => Deposit::where('user_id', $user->id)->get()->toArray(),
            'withdrawals' => Withdraw::where('user_id', $user->id)->get()->toArray(),
        ];

        $filename = "user_data_{$user->id}_{$user->name}_" . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($userData)
                        ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Get user statistics for dashboard
     */
    public function getUserStats()
    {
        $stats = [
            'total_users' => User::where('role', 'USER')->count(),
            'active_users' => User::where('role', 'USER')
                ->whereHas('subscriptions', function($q) {
                    $q->where('expire_at', '>', now());
                })->count(),
            'new_users_today' => User::where('role', 'USER')
                ->whereDate('created_at', today())->count(),
            'new_users_this_week' => User::where('role', 'USER')
                ->where('created_at', '>=', now()->subWeek())->count(),
            'blocked_users' => User::where('role', 'USER')->where('is_blocked', true)->count(),
            'users_with_active_plans' => User::where('role', 'USER')
                ->whereHas('subscriptions', function($q) {
                    $q->where('expire_at', '>', now());
                })->count(),
            'total_deposits_today' => Deposit::where('status', 'approved')
                ->whereDate('created_at', today())->sum('amount'),
            'total_withdrawals_today' => Withdraw::where('status', 'transferred')
                ->whereDate('created_at', today())->sum('amount'),
        ];

        return response()->json($stats);
    }
}
