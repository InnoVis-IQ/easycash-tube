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
            return view('backend.user.investmentPlans.index')->with([
                'data' => UserSubscription::withTrashed()->where('user_id',Auth::user()['id'])->get(),
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
            'limit' => 'nullable|string',
            'status' => 'required|string',
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
            'limit' => 'nullable|string',
            'status' => 'required|string',
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
