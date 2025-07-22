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
        //
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
    public function edit(InvestmentPlan $investmentPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvestmentPlanRequest $request, InvestmentPlan $investmentPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestmentPlan $investmentPlan)
    {
        //
    }
}
