<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\InvestmentPlan;
use App\Models\UserSubscription;
use App\Http\Requests\StoreInvestmentPlanRequest;
use App\Http\Requests\UpdateInvestmentPlanRequest;

class InvestmentPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()['role'] == "USER") {
            // Get all available plans
            $allPlans = InvestmentPlan::where('status', 'active')->get();
            
            // Get user's current subscriptions
            $userSubscriptions = UserSubscription::where('user_id', Auth::user()['id'])
                ->where('expire_at', '>', date('Y-m-d'))
                ->get();
            
            // Get user's wallet balance
            $userAccount = \App\Models\UserAccount::where('user_id', Auth::user()['id'])->first();
            $walletBalance = $userAccount ? $userAccount->net_amount : 0;
            
            // Mark plans as purchased or available
            $purchasedPlanIds = $userSubscriptions->pluck('investment_plan_id')->toArray();
            
            foreach ($allPlans as $plan) {
                $plan->is_purchased = in_array($plan->id, $purchasedPlanIds);
                $plan->can_upgrade = !$plan->is_purchased && $walletBalance >= $plan->amount;
                
                // Add withdrawal restriction info
                $plan->withdrawal_allowed = $plan->limit === 'daily_withdrawal';
                $plan->withdrawal_restriction = $plan->limit === 'no_withdrawal' ? 'No withdrawal allowed' : 'Daily withdrawal allowed';
            }
            
            return view('backend.user.investmentPlans.index')->with([
                'allPlans' => $allPlans,
                'userSubscriptions' => $userSubscriptions,
                'walletBalance' => $walletBalance,
            ]);
        } else {
            return view('backend.admin.investmentPlans.index')->with([
                'data' => InvestmentPlan::withTrashed()->get(),
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()['role'] == "USER") {
            return view('backend.user.investmentPlans.create')->with([
                'data' => InvestmentPlan::withTrashed()->get(),
            ]);
        } else {
            return view('backend.admin.investmentPlans.create')->with([
                'data' => InvestmentPlan::withTrashed()->get(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvestmentPlanRequest $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'daily_profit' => 'required|numeric',
            'plan_days' => 'required|numeric',
            'buffer_days' => 'nullable|numeric',
            'limit' => 'nullable|string|in:no_withdrawal,daily_withdrawal',
            'status' => 'required|string|in:active,inactive',
        ]);

        $validated['total_profit'] = $validated['daily_profit'] * $validated['plan_days'];

        InvestmentPlan::create($validated);

        return redirect()->route('plans.index')
            ->with('success', 'Investment plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InvestmentPlan $investmentPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $investmentPlan = InvestmentPlan::find($id);
        return view('backend.admin.investmentPlans.edit', compact('investmentPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvestmentPlanRequest $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'daily_profit' => 'required|numeric',
            'plan_days' => 'required|numeric',
            'buffer_days' => 'nullable|numeric',
            'limit' => 'nullable|string|in:no_withdrawal,daily_withdrawal',
            'status' => 'required|string|in:active,inactive',
        ]);

        if($validated){
            $validated['total_profit'] = $validated['daily_profit'] * $validated['plan_days'];

            InvestmentPlan::where('id', $id)->update($validated);
        }

        return redirect()->route('plans.index')
            ->with('success', 'Investment plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        InvestmentPlan::where('id', $id)->update(['status' => 'inactive']);
        InvestmentPlan::where('id', $id)->delete();

        return redirect()->route('plans.index')
            ->with('success', 'Investment plan deleted successfully.');
    }
}
