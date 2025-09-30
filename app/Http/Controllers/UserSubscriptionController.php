<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $investmentPlan = InvestmentPlan::where('id', $request['invest'])->where('status', 'active')->first();
        
        // Check if user has already subscribed to this specific plan
        $existingSubscription = UserSubscription::where('user_id', $request['user'])->where('investment_plan_id', $request['invest'])->first();
        if ($existingSubscription) {
            Session::flash('error', 'You have already subscribed to this plan.');
            return back();
        }

        // Check if user already has maximum 2 active subscriptions
        $activeSubscriptions = UserSubscription::where('user_id', $request['user'])
            ->where('expire_at', '>', date('Y-m-d'))
            ->count();

        if ($activeSubscriptions >= 2) {
            Session::flash('error', 'You can have a maximum of 2 active plans at the same time. Please wait for one of your current plans to expire.');
            return redirect()->route('plans.index');
        }

        if ($userAccount['net_amount'] >= $investmentPlan['amount']) {
            $expiry = date("Y-m-d", strtotime("+" . $investmentPlan['plan_days']+$investmentPlan['buffer_days'] . " days", strtotime(date("Y-m-d"))));

            $data = new UserSubscription;
            $data['user_id'] = $request['user'];
            $data['investment_plan_id'] = $request['invest'];
            $data['expire_at'] = $expiry;
            $data->save();

            // Deduct the plan amount from user's wallet
            UserAccount::where('user_id', $request['user'])->decrement('net_amount', $investmentPlan['amount']);

            // Referral bonus: Rs. 100 when referred user buys any plan
            $currentUser = User::where('id', Auth::user()['id'])->first();
            if ($currentUser && $currentUser->referred_by && $currentUser->bonus_given == 0) {
                $referrer = User::where('referral_code', $currentUser->referred_by)->first();
                if ($referrer) {
                    // Give Rs. 100 bonus to referrer
                    UserAccount::where('user_id', $referrer->id)->increment('net_amount', 100);
                    
                    // Mark bonus as given to prevent multiple bonuses
                    User::where('id', Auth::user()['id'])->update(['bonus_given' => 100]);
                }
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
