<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        
        $video = \App\Models\Video::withTrashed()->findOrFail($id);
        return view('backend.user.videos.watch', compact('video'));
    }
}
