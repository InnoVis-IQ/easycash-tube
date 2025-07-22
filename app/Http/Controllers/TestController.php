<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models;

class TestController extends Controller
{
    // This controller is for quick functions

    public function bonusRecalculate(){
        // withdraw earning to account as automatic addition feature was implemented late

        $refs = Models\User::where('bonus_given','>',0)->get();
        foreach ($refs as $key => $value) {
            $sub = Models\UserSubscription::where('user_id',$value['id'])->orderBy('id')->first();
            $investPlan = $sub ? Models\InvestmentPlan::find($sub['investment_plan_id']) : NULL;
            $bonus = $investPlan ? $investPlan['amount']*(0.15) : 0;
            // Models\User::where('id',$value['id'])->update(['bonus_given'=>$bonus]);
        }
    }

    public function resetPassword(){
        // withdraw earning to account as automatic addition feature was implemented late

        // Models\User::where('id',1)->update(['password'=>Hash::make("S.Admin.life.123")]);
    }

    public function withdrawEarningToAccount(){
        // withdraw earning to account as automatic addition feature was implemented late

        $users = Models\UserSubscription::get();
        foreach($users as $user){
            // Models\UserAccount::where('user_id',$user['user_id'])->increment('net_amount',$user['earning']);
        }
    }

}
