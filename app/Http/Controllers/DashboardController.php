<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index() {
        switch (strtolower(Auth::user()['role'])) {
            case 'superadmin':
                $view = $this->admindashboard();
                break;

            case 'admin':
                $view = $this->admindashboard();
                break;

            case 'user':
                $view = $this->userdashboard();
                break;

            case 'restricted':
                return view('backend.restricted');
                break;

            default:
                # code...
                break;
        }
        return $view;
    }

    public function admindashboard(){
        // Basic statistics
        $newUsers = Models\User::where('created_at','>=',date('Y-m-d H:i:s',strtotime("-3 day",strtotime(date("Y-m-d H:i:s")))))->count();
        $pendingDeposits = Models\Deposit::where('status','deposited')->count();
        $activeSubscriptions = Models\UserSubscription::where('expire_at','>',date('Y-m-d'))->count();
        
        // Enhanced statistics
        $totalUsers = Models\User::count();
        $totalActiveUsers = Models\User::where('role', 'user')->count();
        $totalWithdrawals = Models\Withdraw::count();
        $pendingWithdrawals = Models\Withdraw::where('status', 'requested')->count();
        $completedWithdrawals = Models\Withdraw::where('status', 'transferred')->count();
        
        // Plan breakdown
        $planBreakdown = Models\InvestmentPlan::withTrashed()->get()->map(function($plan) {
            $activeCount = Models\UserSubscription::where('investment_plan_id', $plan->id)
                ->where('expire_at', '>', date('Y-m-d'))
                ->count();
            return [
                'plan_name' => $plan->title,
                'active_count' => $activeCount,
                'amount' => $plan->amount
            ];
        });
        
        // Earnings and payout summary
        $totalInvestedAmount = 0;
        $totalEarnings = 0;
        $subscriptions = Models\UserSubscription::where('expire_at','>',date('Y-m-d'))->get();
        
        foreach ($subscriptions as $subscription) {
            $investmentPlan = Models\InvestmentPlan::find($subscription['investment_plan_id']);
            $totalInvestedAmount += $investmentPlan['amount'];
            $totalEarnings += $subscription['earning'];
        }
        
        // Recent activities
        $recentUsers = Models\User::orderBy('created_at', 'desc')->take(5)->get();
        $recentWithdrawals = Models\Withdraw::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $recentDeposits = Models\Deposit::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        
        // Get active announcements
        $announcements = $this->getActiveAnnouncements();
        
        $message = "0";

        return view('backend.admin.dashboard',compact(
            'message',
            'newUsers',
            'pendingDeposits',
            'activeSubscriptions',
            'totalUsers',
            'totalActiveUsers',
            'totalWithdrawals',
            'pendingWithdrawals',
            'completedWithdrawals',
            'planBreakdown',
            'totalInvestedAmount',
            'totalEarnings',
            'recentUsers',
            'recentWithdrawals',
            'recentDeposits',
            'announcements'
        ));
    }
    
    public function userdashboard(){
        Session::put('UserSessionVars',[]);

        // Common variables for all users
        $userId = Auth::user()['id'];
        $message = "0";
        
        // Get wallet data
        $mainWallet = \App\Models\UserWallet::getOrCreateWallet($userId, 'main');
        $earnWallet = \App\Models\UserWallet::getOrCreateWallet($userId, 'earn');
        $totalWalletBalance = \App\Models\UserWallet::getTotalBalance($userId);
        $withdrawableBalance = \App\Models\UserWallet::getWithdrawableBalance($userId);
        
        // Calculate total earned
        $totalEarned = ($earnWallet->total_earned ?? 0) + ($mainWallet->total_earned ?? 0);
        
        // Get common stats
        $pendingDeposits = Models\Deposit::where('user_id',$userId)->where('status','deposited')->count();
        $totalDeposits = Models\Deposit::where('user_id', $userId)->where('status', 'approved')->sum('amount');
        $totalWithdrawals = Models\Withdraw::where('user_id', $userId)->where('status', 'approved')->sum('amount');
        $walletBalance = optional(Models\UserAccount::where('user_id', $userId)->first())->net_amount ?? 0;
        
        // Get referral count
        $referralCount = Models\User::where('referred_by', Auth::user()->referral_code)->count();
        
        // Get task completion count (placeholder - implement based on your task system)
        $taskCount = 9; // This should be calculated based on actual task completion
        
        // Get active announcements
        $announcements = $this->getActiveAnnouncements();
        
        // Get all plans for display
        $allPlans = Models\InvestmentPlan::withTrashed()->get();

        // Check if user has subscriptions
        if(!Models\UserSubscription::where('user_id',$userId)->count()){
            // User has NO subscriptions
            $subscription_status = 'no';
            $currentSubscription = null;
            $activeSubscriptions = 0;
            $subscriptions = collect();
            $investedAmount = 0;
            $upcomingIncome = 0;
            $earningsToday = 0;
            $daysWatched = 0;
            $totalPlanDays = 0;
            $userLevel = 1;
            $levelProgress = 0;
            $watchedVideosProgress = 0;
            $totalClicks = 0;
            $todayReward = 0;
        }
        else{
            // User HAS subscriptions
            $subscription_status = 'yes';
            $activeSubscriptions = Models\UserSubscription::where('user_id',$userId)->where('expire_at','>',date('Y-m-d'))->count();

            $subscriptions = Models\UserSubscription::where('user_id',$userId)->where('expire_at','>',date('Y-m-d'))->get();
            $investedAmount = 0;
            $upcomingIncome = 0;
            $earningsToday = 0;
            $daysWatched = 0;
            $totalPlanDays = 0;
            
            foreach ($subscriptions as $key => $value) {
                $investmentPlan = Models\InvestmentPlan::find($value['investment_plan_id']);
                $investedAmount += $investmentPlan['amount'];
                if($value['expire_at'] <= date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))))){
                    $upcomingIncome += $investmentPlan['amount'];
                }
                // Earnings today: check if updated today (assuming daily profit is credited once per day)
                if ($value->updated_at && $value->updated_at->isToday()) {
                    $earningsToday += $investmentPlan['daily_profit'];
                }
                // Days watched: count watched_videos array
                if (is_array($value->watched_videos)) {
                    $daysWatched += count($value->watched_videos);
                } elseif ($value->watched_videos) {
                    $decoded = json_decode($value->watched_videos, true);
                    if (is_array($decoded)) {
                        $daysWatched += count($decoded);
                    }
                }
                $totalPlanDays += $investmentPlan['plan_days'];
            }
            
            // Calculate user level and progress
            $userLevel = $this->calculateUserLevel($userId, $daysWatched);
            $levelProgress = $this->calculateLevelProgress($userId, $userLevel);
            
            // Calculate watched videos progress for current subscription (5 days requirement)
            $watchedVideosProgress = $this->calculateWatchedVideosProgress($userId);
            
            // Get total clicks (video watches)
            $totalClicks = $daysWatched;
            
            // Calculate today's reward (daily video reward)
            $todayReward = 0;
            $currentSubscription = Models\UserSubscription::where('user_id', $userId)
                ->where('expire_at', '>', date('Y-m-d'))
                ->orderBy('created_at', 'desc')
                ->first();
            if ($currentSubscription) {
                $today = date('Y-m-d');
                $watchedVideos = $currentSubscription->watched_videos;
                $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);
                
                // Check if user watched video today
                if (in_array($today, $watchedVideos)) {
                    $todayReward = $currentSubscription->plan ? $currentSubscription->plan->daily_profit : 0;
                }
            }
        }
        
        // Check if bonus video was watched today and add 25 to todayReward
        $userAccount = Models\UserAccount::where('user_id', $userId)->first();
        if ($userAccount && $userAccount->bonus_videos_watched) {
            $bonusVideosWatched = is_array($userAccount->bonus_videos_watched) 
                ? $userAccount->bonus_videos_watched 
                : (json_decode($userAccount->bonus_videos_watched, true) ?: []);
            
            $today = date('Y-m-d');
            if (in_array($today, $bonusVideosWatched)) {
                $todayReward += 25;
            }
        }
        
        // Return view with all variables
        return view('backend.user.dashboard',compact(
            'message',
            'subscription_status',
            'currentSubscription',
            'pendingDeposits',
            'activeSubscriptions', 
            'subscriptions',
            'investedAmount',
            'upcomingIncome',
            'walletBalance',
            'earningsToday',
            'daysWatched',
            'totalPlanDays',
            'userLevel',
            'levelProgress',
            'watchedVideosProgress',
            'referralCount',
            'taskCount',
            'totalClicks',
            'totalDeposits',
            'mainWallet',
            'earnWallet',
            'totalWalletBalance',
            'withdrawableBalance',
            'totalEarned',
            'todayReward',
            'totalWithdrawals',
            'announcements',
            'allPlans'
        ));
    }
    
    /**
     * Calculate user level based on activities
     */
    private function calculateUserLevel($userId, $daysWatched) {
        // Simple level calculation based on days watched
        if ($daysWatched >= 50) return 5;
        if ($daysWatched >= 35) return 4;
        if ($daysWatched >= 25) return 3;
        if ($daysWatched >= 15) return 2;
        return 1;
    }
    
    /**
     * Calculate level progress percentage
     */
    private function calculateLevelProgress($userId, $currentLevel) {
        $user = Models\User::find($userId);
        $daysWatched = 0;
        
        // Get total days watched from subscriptions
        $subscriptions = Models\UserSubscription::where('user_id', $userId)->get();
        foreach ($subscriptions as $subscription) {
            if (is_array($subscription->watched_videos)) {
                $daysWatched += count($subscription->watched_videos);
            } elseif ($subscription->watched_videos) {
                $decoded = json_decode($subscription->watched_videos, true);
                if (is_array($decoded)) {
                    $daysWatched += count($decoded);
                }
            }
        }
        
        // Calculate progress based on current level requirements
        $levelRequirements = [
            1 => 0,
            2 => 15,
            3 => 25,
            4 => 35,
            5 => 50
        ];
        
        $currentRequirement = $levelRequirements[$currentLevel] ?? 0;
        $nextRequirement = $levelRequirements[$currentLevel + 1] ?? $currentRequirement + 10;
        
        if ($currentLevel >= 5) {
            return 100; // Max level reached
        }
        
        $progress = (($daysWatched - $currentRequirement) / ($nextRequirement - $currentRequirement)) * 100;
        return max(0, min(100, $progress));
    }
    
    /**
     * Calculate watched videos progress for current subscription (5 days requirement)
     */
    private function calculateWatchedVideosProgress($userId) {
        // Get the user's current active subscription
        $currentSubscription = Models\UserSubscription::where('user_id', $userId)
            ->where('expire_at', '>', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();
            
        if (!$currentSubscription) {
            return 0; // No active subscription
        }
        
        // Get watched videos for this subscription
        $watchedVideos = $currentSubscription->watched_videos;
        $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);
        
        // Count unique days watched (in case there are multiple videos per day)
        $uniqueDaysWatched = count(array_unique($watchedVideos));
        
        // Calculate progress based on 5 days requirement
        $requiredDays = 5;
        $progress = ($uniqueDaysWatched / $requiredDays) * 100;
        
        return min(100, $progress); // Cap at 100%
    }
    
    /**
     * Get active announcements for the current user
     */
    private function getActiveAnnouncements() {
        $user = Auth::user();

        return Announcement::where('target_users','all')->where('is_active',true)->where('expires_at','>',now())->get();
    }
    
    /**
     * Mark bonus video as watched and award bonus reward
     */
    public function markVideoAsWatched(Request $request) {
        try {
            $request->validate([
                'video_id' => 'required|string',
                'watch_time' => 'required|numeric',
                'seek_attempts' => 'required|integer'
            ]);
            
            $userId = Auth::user()->id;
            
            // Check if user has already watched bonus video today
            $today = date('Y-m-d');
            $bonusVideoKey = 'bonus_video_' . $today;
            
            // Get or create user account
            $userAccount = Models\UserAccount::where('user_id', $userId)->first();
            if (!$userAccount) {
                $userAccount = Models\UserAccount::create([
                    'user_id' => $userId,
                    'net_amount' => 0,
                    'bonus_videos_watched' => []
                ]);
            }
            
            // Check if bonus video already watched today
            $bonusVideosWatched = $userAccount->bonus_videos_watched ?? [];
            if (is_string($bonusVideosWatched)) {
                $bonusVideosWatched = json_decode($bonusVideosWatched, true) ?: [];
            }
            
            if (in_array($today, $bonusVideosWatched)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already watched the bonus video today.'
                ]);
            }
            
            // Validate watch time (at least 90% of video duration)
            $watchPercentage = ($request->watch_time / $request->video_duration) * 100;
            
            if ($watchPercentage < 90) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must watch at least 90% of the video to earn the bonus reward.'
                ]);
            }
            
            // Check for excessive seeking
            if ($request->seek_attempts >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many skip attempts detected. Please watch the video without skipping.'
                ]);
            }
            
            // Calculate bonus reward (e.g., 25 PKR)
            $bonusReward = 25;
            
            // Add bonus reward to user's main wallet
            $mainWallet = \App\Models\UserWallet::getOrCreateWallet($userId, 'main');
            $mainWallet->addFunds($bonusReward, 'earned');
            
            // Also add to legacy UserAccount for backward compatibility
            $userAccount->net_amount += $bonusReward;
            
            // Mark bonus video as watched for today
            $bonusVideosWatched[] = $today;
            $userAccount->bonus_videos_watched = $bonusVideosWatched;
            
            $userAccount->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Bonus video marked as watched successfully!',
                'bonus_reward' => $bonusReward
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request: ' . $e->getMessage()
            ]);
        }
    }
}
