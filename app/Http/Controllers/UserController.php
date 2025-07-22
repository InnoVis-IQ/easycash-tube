<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAccount;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\UserSubscription;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $referrals = [];
        $users = User::withTrashed()->get();
        foreach ($users as $user) {
            $referrals[$user['referral_code']] = $user['name'];
        }
        return view('backend.admin.users.index')->with([
            'data' => $users,
            'referrals' => $referrals
        ]);
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
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('backend.admin.users.show')->with([
            'data' => $user,
            'referrals' => User::withTrashed()->get(),
            'subscriptions' => UserSubscription::with(['plan'])->where('user_id',$user['id'])->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($request['role'] == 'approve'){
            $user['role'] = 'USER';
            $user->save();

            $userAccount = new UserAccount;
            $userAccount['user_id'] = $user['id'];
            $userAccount['net_amount'] = 0;
            $userAccount->save();

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // dd($user);
        $user->delete();

        return back();
    }

}
