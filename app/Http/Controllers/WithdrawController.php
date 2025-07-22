<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Withdraw;
use App\Models\UserSubscription;
use App\Models\UserAccount;
use App\Models\UserBankAccount;
use App\Models\BankAccount;
use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userAccounts = UserAccount::withTrashed()->get();
        $accountBalance = [];
        foreach ($userAccounts as $userAccount) {
            $accountBalance[$userAccount['user_id']] = $userAccount['net_amount'];
        }
        if(in_array(Auth::user()['role'],['SUPERADMIN','ADMIN'])){
            return view('backend.admin.withdraw.index')->with([
                'data' => Withdraw::withTrashed()->orderBy('status')->get(),
                'pending' => Withdraw::where('status','requested')->count(),
                'accountBalance' => $accountBalance,
                'banks' => BankAccount::get()
            ]);
        }
        else{
            $userAccount = UserAccount::where('user_id',Auth::user()['id'])->first();
            $userWithdraws = Withdraw::where('user_id',Auth::user()['id'])->where('status','requested')->sum('amount');
            $netAmount = $userAccount['net_amount'] - $userWithdraws;
            $data = [];
            return view('backend.user.withdraw.index', compact('netAmount','userWithdraws','data'));
        }
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
    public function store(StoreWithdrawRequest $request)
    {
        $message = '0';
        $userBankAccount = UserBankAccount::where('account_number',$request['acc_number'])->first();

        if(!$userBankAccount){
            $newAccount = new UserBankAccount;
            $newAccount['user_id'] = $request['userID'];
            $newAccount['account_title'] = $request['acc_title'];
            $newAccount['account_number'] = $request['acc_number'];
            $newAccount->save();

            $userBankAccountID = $newAccount->id;
        }
        else{
            $userBankAccountID = $userBankAccount['id'];
        }

        $userAccount = UserAccount::where('user_id',Auth::user()['id'])->first();
        $userWithdraws = Withdraw::where('user_id',Auth::user()['id'])->where('status','requested')->sum('amount');
        $netAmount = $userAccount['net_amount'] - $userWithdraws;

        if($netAmount >= $request['amount']){

            $withdrawRequest = new Withdraw;
            $withdrawRequest['user_id'] = $request['userID'];
            $withdrawRequest['amount'] = $request['amount'];
            $withdrawRequest['user_bank_account_id'] = $userBankAccountID;
            $withdrawRequest->save();
        }
        else{
            $message = "Insufficient Balance";
        }

        return back()->with(['message'=>$message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdraw $withdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        $withdraw['bank_account_id'] = $request['bank'];
        $withdraw['status'] = 'transferred';
        $withdraw->save();

        UserAccount::where('user_id',$withdraw['user_id'])->decrement('net_amount', $withdraw['amount']);

        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }

    public function withdrawToAccount(Request $request){
        // UserSubscription::where('id',$request['userSubsID'])->update(['earning'=>0]);
        // UserAccount::where('user_id',$request['userID'])->increment('net_amount', $request['amount']);

        // return back()->with(['message'=>'Amount transferred to Wallet']);
    }
}
