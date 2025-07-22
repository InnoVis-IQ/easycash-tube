<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models;

class SuperadminController extends Controller
{
    public function adjustUserAccount($id){
        if(Auth::user()['role'] == 'SUPERADMIN'){
            $dep = Models\Deposit::where('user_id',$id)->sum('amount');
            $earning = Models\UserSubscription::where('user_id',$id)->sum('earning');
            $draw = Models\Withdraw::where('user_id',$id)->where('status','transferred')->sum('amount');

            $subs = Models\UserSubscription::where('user_id',$id)->get();
            $sub = 0;
            foreach ($subs as $key => $value) {
                $investAmmount = Models\InvestmentPlan::find($value['investment_plan_id']);
                $sub += $investAmmount['amount'];
            }

            $refCode = Models\User::where('id',$id)->first('referral_code');
            $refs = Models\User::where('referred_by',$refCode['referral_code'])->where('bonus_given','>',0)->get();

            $bonus = 0;
            foreach ($refs as $key => $value) {
                $bonus += $value['bonus_given'];
            }

            $netAmount = $dep-$sub+$earning-$draw+$bonus;
            Models\UserAccount::where('user_id',$id)->update(['net_amount'=>$netAmount]);
            // dd($dep,$sub,$earning,$draw,$bonus,$dep-$sub+$earning-$draw+$bonus,$refs);
            return back();
        }
        else{
            dd("Not Authorized");
        }
    }
}
