<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = \App\Models\Video::withTrashed()->get();
        
        // Get all user subscriptions with watched videos
        $userSubscriptions = \App\Models\UserSubscription::whereNotNull('watched_videos')->get();
        
        // Count each video watched
        $videoWatchCounts = [];
        foreach ($userSubscriptions as $subscription) {
            $watchedVideos = $subscription->watched_videos;
            if (is_array($watchedVideos)) {
                foreach ($watchedVideos as $videoId) {
                    if (!isset($videoWatchCounts[$videoId])) {
                        $videoWatchCounts[$videoId] = 0;
                    }
                    $videoWatchCounts[$videoId]++;
                }
            }
        }
        
        return view('backend.admin.videos.index', compact('data', 'videoWatchCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = \App\Models\InvestmentPlan::withTrashed()->get();
        return view('backend.admin.videos.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string',
            'video_thumbnail' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'status' => 'required|string',
        ]);
        \App\Models\Video::create($validated);
        return redirect()->route('videos.index')->with('success', 'Video created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $video = \App\Models\Video::withTrashed()->findOrFail($id);
        $plans = \App\Models\InvestmentPlan::withTrashed()->get();
        return view('backend.admin.videos.edit', compact('video', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string',
            'video_thumbnail' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'status' => 'required|string',
        ]);
        \App\Models\Video::where('id', $id)->update($validated);
        return redirect()->route('videos.index')->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        \App\Models\Video::where('id', $id)->update(['status' => 'inactive']);
        \App\Models\Video::where('id', $id)->delete();
        return redirect()->route('videos.index')->with('success', 'Video deleted successfully.');
    }

    public function watch()
    {
        // Get all active subscriptions for the user (max 2)
        $subscribedPlans = \App\Models\UserSubscription::where('user_id', Auth::user()['id'])
            ->where('expire_at', '>', date('Y-m-d'))
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();

        if ($subscribedPlans->isEmpty()) {
            return redirect()->route('plans.index')->with('error', 'You are not subscribed to any plan.');
        }

        $today = date('Y-m-d');
        
        // Prepare data for each subscription
        $subscriptionData = [];
        
        foreach ($subscribedPlans as $subscription) {
            $rewardClaimedDate = date('Y-m-d', strtotime($subscription->daily_reward_claimed));
            $watchedVideos = $subscription->watched_videos;
            $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);

            // Check if user has watched a video today for this subscription
            $watchedToday = ($rewardClaimedDate == $today);
            $dailyRewardClaimed = ($rewardClaimedDate == $today);

            // Get all videos for this subscription's plan that are active and not expired
            $videos = \App\Models\Video::where('plan_id', $subscription->investment_plan_id)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                            ->orWhere('expiry_date', '>', now());
                })
                ->get();

            // Progress bar: days watched / plan days
            $daysWatched = is_array($watchedVideos) ? count($watchedVideos) : 0;
            $totalPlanDays = $subscription->plan ? $subscription->plan->plan_days : 0;
            $dailyRewardAmount = $subscription->plan ? $subscription->plan->daily_profit : 0;

            $subscriptionData[] = [
                'subscription' => $subscription,
                'plan' => $subscription->plan,
                'videos' => $videos,
                'watchedVideos' => $watchedVideos,
                'watchedToday' => $watchedToday,
                'dailyRewardClaimed' => $dailyRewardClaimed,
                'daysWatched' => $daysWatched,
                'totalPlanDays' => $totalPlanDays,
                'dailyRewardAmount' => $dailyRewardAmount
            ];
        }

        return view('backend.user.videos.watch', compact('subscriptionData'));
    }

    /**
     * Mark a video as watched for the authenticated user
     */
    public function markAsWatched(Request $request)
    {
        Log::info('markAsWatched called with data:', $request->all());
        
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'watch_time' => 'required|numeric',
            'video_duration' => 'required|numeric',
            'seek_attempts' => 'required|integer'
        ]);

        $videoId = $request->video_id;
        $userId = Auth::user()->id;

        Log::info('Processing video_id: ' . $videoId . ' for user_id: ' . $userId);

        // Validate watch time (at least 90% of video duration)
        $watchPercentage = ($request->watch_time / $request->video_duration) * 100;
        
        if ($watchPercentage < 90) {
            Log::warning('User attempted to claim reward with insufficient watch time. User: ' . $userId . ', Watch percentage: ' . $watchPercentage . '%');
            return response()->json([
                'success' => false,
                'message' => 'You must watch at least 90% of the video to earn your daily reward.'
            ]);
        }
        
        // Check for excessive seeking
        if ($request->seek_attempts >= 3) {
            Log::warning('User attempted to claim reward with excessive skip attempts. User: ' . $userId . ', Skip attempts: ' . $request->seek_attempts);
            return response()->json([
                'success' => false,
                'message' => 'Too many skip attempts detected. Please watch the video without skipping to earn your daily reward.'
            ]);
        }

        // Get the video to find its plan
        $video = \App\Models\Video::find($videoId);
        if (!$video) {
            Log::error('Video not found: ' . $videoId);
            return response()->json(['success' => false, 'message' => 'Video not found.'], 400);
        }

        // Get the user's active subscription for this video's plan
        $subscribedPlan = \App\Models\UserSubscription::where('user_id', $userId)
            ->where('investment_plan_id', $video->plan_id)
            ->where('expire_at', '>', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscribedPlan) {
            Log::error('No active subscription found for user: ' . $userId . ' for plan: ' . $video->plan_id);
            return response()->json(['success' => false, 'message' => 'No active subscription found for this video\'s plan.'], 400);
        }

        Log::info('Found subscription:', $subscribedPlan->toArray());

        // Check if user has already watched a video today for this subscription
        $today = date('Y-m-d');
        if ($this->hasWatchedVideoToday($subscribedPlan, $today)) {
            return response()->json([
                'success' => false, 
                'message' => 'You have already watched a video today for this plan. Come back tomorrow!',
                'video_id' => $videoId
            ]);
        }

        // Get current watched videos
        $watchedVideos = $subscribedPlan->watched_videos;
        $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);

        Log::info('Current watched videos:', $watchedVideos);

        // Add video to watched list if not already watched
        if (!in_array($videoId, $watchedVideos)) {
            $watchedVideos[] = $videoId;
            
            Log::info('Adding video to watched list. New list:', $watchedVideos);
            
            // Calculate daily reward
            $dailyRewardAmount = $subscribedPlan->plan->daily_profit;
            
            // Update the subscription with new watched videos and daily reward
            $result = $subscribedPlan->update([
                'watched_videos' => $watchedVideos,
                'earning' => $subscribedPlan->earning + $dailyRewardAmount,
                'daily_reward_claimed' => $today
            ]);

            // Update user's earn wallet balance
            $earnWallet = \App\Models\UserWallet::getOrCreateWallet($userId, 'earn');
            $earnWallet->addFunds($dailyRewardAmount, 'earned');
            
            // Also update the legacy UserAccount for backward compatibility
            $userAccount = \App\Models\UserAccount::where('user_id', $userId)->first();
            if ($userAccount) {
                $userAccount->update([
                    'net_amount' => $userAccount->net_amount + $dailyRewardAmount
                ]);
            } else {
                \App\Models\UserAccount::create([
                    'user_id' => $userId,
                    'net_amount' => $dailyRewardAmount,
                    'total_earned' => $dailyRewardAmount,
                    'total_withdrawn' => 0
                ]);
            }

            Log::info('Update result:', ['success' => $result]);

            return response()->json([
                'success' => true, 
                'message' => 'Video watched successfully! Daily reward of Rs' . $dailyRewardAmount . ' added to your wallet.',
                'video_id' => $videoId,
                'daily_reward' => $dailyRewardAmount
            ]);
        }

        Log::info('Video was already marked as watched');

        return response()->json([
            'success' => true, 
            'message' => 'Video was already marked as watched.',
            'video_id' => $videoId
        ]);
    }

    /**
     * Get video watch counts for all videos
     */
    public function getVideoWatchCounts()
    {
        $userSubscriptions = UserSubscription::whereNotNull('watched_videos')->get();
        
        $videoWatchCounts = [];
        foreach ($userSubscriptions as $subscription) {
            $watchedVideos = $subscription->watched_videos;
            if (is_array($watchedVideos)) {
                foreach ($watchedVideos as $videoId) {
                    if (!isset($videoWatchCounts[$videoId])) {
                        $videoWatchCounts[$videoId] = 0;
                    }
                    $videoWatchCounts[$videoId]++;
                }
            }
        }
        
        return $videoWatchCounts;
    }

    /**
     * Check if user has watched a video today
     */
    private function hasWatchedVideoToday($subscribedPlan, $today)
    {
        $rewardClaimedDate = date('Y-m-d', strtotime($subscribedPlan->daily_reward_claimed));
        return $rewardClaimedDate == $today;
    }
}
