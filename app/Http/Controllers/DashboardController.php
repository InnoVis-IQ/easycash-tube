<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models;

class DashboardController extends Controller
{
    public function index() {
        switch (strtolower(Auth::user()['role'])) {
            case 'superadmin':
                $view = $this->admindashboard();
                break;

            case 'admin':
                $view = $this->admindashboard();
                break;

            case 'user':
                $view = $this->userdashboard();
                break;

            case 'restricted':
                return view('backend.restricted');
                break;

            default:
                # code...
                break;
        }
        return $view;
    }

    public function admindashboard(){
        $newUsers = Models\User::where('created_at','>=',date('Y-m-d H:i:s',strtotime("-3 day",strtotime(date("Y-m-d H:i:s")))))->count();
        $pendingDeposits = Models\Deposit::where('status','deposited')->count();
        $activeSubscriptions = Models\UserSubscription::where('expire_at','>',date('Y-m-d'))->count();

        $subscriptions = Models\UserSubscription::where('expire_at','>',date('Y-m-d'))->get();
        $investedAmount = 0;
        foreach ($subscriptions as $key => $value) {
            $investmentPlan = Models\InvestmentPlan::find($value['investment_plan_id']);
            $investedAmount += $investmentPlan['amount'];
        }
        $message = "0";

        return view('backend.admin.dashboard',compact('message','newUsers','pendingDeposits','activeSubscriptions','investedAmount'));
    }
    public function userdashboard(){
        Session::put('UserSessionVars',[]);

        if(!Models\UserSubscription::where('user_id',Auth::user()['id'])->count()){
            return redirect()->route('plans.create');
        }
        else{
            $pendingDeposits = Models\Deposit::where('user_id',Auth::user()['id'])->where('status','deposited')->count();
            $activeSubscriptions = Models\UserSubscription::where('user_id',Auth::user()['id'])->where('expire_at','>',date('Y-m-d'))->count();

            $subscriptions = Models\UserSubscription::where('user_id',Auth::user()['id'])->where('expire_at','>',date('Y-m-d'))->get();
            $investedAmount = 0;
            $upcomingIncome = 0;
            foreach ($subscriptions as $key => $value) {
                $investmentPlan = Models\InvestmentPlan::find($value['investment_plan_id']);
                $investedAmount += $investmentPlan['amount'];

                if($value['expire_at'] <= date('Y-m-d',strtotime('+30 days',strtotime(date('Y-m-d'))))){
                    $upcomingIncome += $investmentPlan['amount'];
                }
            }
            $message = "0";
            return view('backend.user.dashboard',compact('message','pendingDeposits','activeSubscriptions', 'subscriptions','investedAmount','upcomingIncome'));
        }
    }
}
