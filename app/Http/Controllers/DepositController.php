<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;

use App\Models\Deposit;
use App\Models\BankAccount;
use App\Models\UserAccount;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(in_array(Auth::user()['role'],['SUPERADMIN','ADMIN'])){
            return view('backend.admin.deposit.index')->with([
                'data' => Deposit::orderBy('status','DESC')->get(),
                'pending' => Deposit::where('status','deposited')->count()
            ]);
        }
        else{
            return view('backend.user.deposit.index')->with([
                'data' => Deposit::where('user_id', Auth::user()['id'])->get(),
                'totalDeposit' => Deposit::where('user_id', Auth::user()['id'])->sum('amount'),
                'approvedDeposit' => Deposit::where('user_id', Auth::user()['id'])->where('status','approved')->sum('amount')
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.user.deposit.create')->with([
            'banks' => BankAccount::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepositRequest $request)
    {
        // $validated = $request->validated();
        $file = $request->file('proof');

        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $path = $request->file('proof')->store('public/depositProof');

        if(in_array($extension,['jpg','png'])){
            $data = new Deposit;
            $data['user_id'] = $request['user'];
            $data['bank_account_id'] = $request['bank'];
            $data['amount'] = $request['amount'];
            $data['transaction_id'] = $request['transaction_id'];
            $data['deposit_date'] = $request['date'];
            $data['picture_proof'] = Str::replace("public/","",$path);
            $data->save();

            return redirect()->route('deposits.index')->with(['message'=>'Deposited, Please wait for apprp']);
        }
        else{
            return back()->with(['error'=>'Invalid file type. Only jpg or png files are allowed']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepositRequest $request, Deposit $deposit)
    {
        if($request['status'] == 'approve' && in_array(Auth::user()['role'],['SUPERADMIN','ADMIN']))
        {
            if($deposit['status'] != 'approved'){
                $deposit['status'] = 'approved';
                $deposit->save();

                UserAccount::where('user_id',$deposit['user_id'])->increment('net_amount', $deposit['amount']);
            }

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        $deposit->delete();

        return back();
    }
}
