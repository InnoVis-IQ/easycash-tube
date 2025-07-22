<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\InvestmentPlan;

class FrontEndController extends Controller
{
    public function index(){
        // return redirect()->route('login');
        return view('frontend.web.index')->with([
            'data' => InvestmentPlan::get(),
        ]);
    }
}
