<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subscriptionPlans = SubscriptionPlan::latest()->paginate(10);
        return view('subscription-plans.index', compact('subscriptionPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('subscription-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'daily_earning' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlan $subscriptionPlan): View
    {
        return view('subscription-plans.show', compact('subscriptionPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        return view('subscription-plans.edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'daily_earning' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1',
        ]);

        $subscriptionPlan->update($validated);

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->delete();

        return redirect()->route('subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
} 