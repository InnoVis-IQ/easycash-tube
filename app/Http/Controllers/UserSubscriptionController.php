<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\UserSubscription;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\ProfitRecord;
use App\Http\Requests\StoreUserSubscriptionRequest;
use App\Http\Requests\UpdateUserSubscriptionRequest;

class UserSubscriptionController extends Controller
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
    public function store(StoreUserSubscriptionRequest $request)
    {
        $userAccount = UserAccount::where('user_id', $request['user'])->first();
        $investmentPlan = InvestmentPlan::find($request['invest']);

        if ($userAccount['net_amount'] >= $investmentPlan['amount']) {
            $expiry = date("Y-m-d", strtotime("+" . $investmentPlan['plan_days'] . " days", strtotime(date("Y-m-d"))));

            $data = new UserSubscription;
            $data['user_id'] = $request['user'];
            $data['investment_plan_id'] = $request['invest'];
            $data['expire_at'] = $expiry;
            $data->save();

            UserAccount::where('user_id', $request['user'])->decrement('net_amount', $investmentPlan['amount']);

            // 15% referral bonus
            $isReferred = User::where('id', Auth::user()['id'])->where('bonus_given', 0)->first();
            // dd(round(0.15*$investmentPlan['amount']));
            if ($isReferred) {
                $referredBy = User::where('referral_code', $isReferred['referred_by'])->first('id');
                UserAccount::where('user_id', $referredBy['id'])->increment('net_amount', round(0.15 * $investmentPlan['amount']));
                User::where('id', Auth::user()['id'])->update(['bonus_given' => round(0.15 * $investmentPlan['amount'])]);
            }

            return redirect()->route('plans.index');
        } else {
            Session::put('UserSessionVars.InsufficientBalance', 1);
            return redirect()->route('deposits.index')->with(['message' => 'Insufficient balance']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSubscription $userSubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserSubscription $userSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSubscriptionRequest $request, UserSubscription $userSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSubscription $userSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function profitCalculation(UserSubscription $userSubscription)
    {
        // $oneDayAgo = date('Y-m-d H:i:s',strtotime('-1 day',strtotime(date('Y-m-d H:i:s'))));
        // $userSubscriptions = UserSubscription::where('expire_at','>=',date('Y-m-d'))->where('created_at','<',$oneDayAgo)->get();
        $userSubscriptions = UserSubscription::where('expire_at', '>=', date('Y-m-d'))->get();

        if (!ProfitRecord::where('date', date('Y-m-d'))->first()) {
            $profitRecord = new ProfitRecord;
            $profitRecord['date'] = date('Y-m-d');
            $profitRecord->save();

            foreach ($userSubscriptions as $userSubscription) {
                // $userSubscription['earning'] += $userSubscription['plan']['daily_profit'];
                // $userSubscription->save();

                UserSubscription::where('id', $userSubscription['id'])->increment('earning', $userSubscription['plan']['daily_profit']);
                UserAccount::where('user_id', $userSubscription['user_id'])->increment('net_amount', $userSubscription['plan']['daily_profit']);
            }
            return back()->with(['message'=>'Profit for today is added successfully.']);
        } else {
            return back()->with(['message'=>'Profit for today is already added.']);
        }
    }
}
