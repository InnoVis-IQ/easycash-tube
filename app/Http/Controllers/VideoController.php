<?php

namespace App\Http\Controllers;

use App\Models\Video;
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
        return view('backend.admin.videos.index', compact('data'));
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
        $subscribedPlan = \App\Models\UserSubscription::where('user_id', Auth::user()['id'])
            ->where('expire_at', '>', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscribedPlan) {
            return redirect()->route('plans.index')->with('error', 'You are not subscribed to any plan.');
        }

        $watchedVideos = $subscribedPlan['watched_videos'];
        $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);

        // Get all videos for the user's subscribed plan that are active and not expired
        $videos = \App\Models\Video::where('plan_id', $subscribedPlan->investment_plan_id)
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>', now());
            })
            ->get();

        if (!$videos) {
            return redirect()->route('plans.index')->with('error', 'No new videos available for your plan.');
        }

        return view('backend.user.videos.watch', compact('videos','watchedVideos'));
    }

    /**
     * Mark a video as watched for the authenticated user
     */
    public function markAsWatched(Request $request)
    {
        Log::info('markAsWatched called with data:', $request->all());
        
        $request->validate([
            'video_id' => 'required|exists:videos,id'
        ]);

        $videoId = $request->video_id;
        $userId = Auth::user()->id;

        Log::info('Processing video_id: ' . $videoId . ' for user_id: ' . $userId);

        // Get the user's active subscription
        $subscribedPlan = \App\Models\UserSubscription::where('user_id', $userId)
            ->where('expire_at', '>', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$subscribedPlan) {
            Log::error('No active subscription found for user: ' . $userId);
            return response()->json(['success' => false, 'message' => 'No active subscription found.'], 400);
        }

        Log::info('Found subscription:', $subscribedPlan->toArray());

        // Get current watched videos
        $watchedVideos = $subscribedPlan->watched_videos;
        $watchedVideos = is_array($watchedVideos) ? $watchedVideos : (json_decode($watchedVideos, true) ?: []);

        Log::info('Current watched videos:', $watchedVideos);

        // Add video to watched list if not already watched
        if (!in_array($videoId, $watchedVideos)) {
            $watchedVideos[] = $videoId;
            
            Log::info('Adding video to watched list. New list:', $watchedVideos);
            
            // Update the subscription with new watched videos
            $result = $subscribedPlan->update([
                'watched_videos' => $watchedVideos
            ]);

            Log::info('Update result:', ['success' => $result]);

            return response()->json([
                'success' => true, 
                'message' => 'Video marked as watched successfully.',
                'video_id' => $videoId
            ]);
        }

        Log::info('Video was already marked as watched');

        return response()->json([
            'success' => true, 
            'message' => 'Video was already marked as watched.',
            'video_id' => $videoId
        ]);
    }
}
