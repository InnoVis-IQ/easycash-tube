<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccount;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('frontend.register');
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:15', 'unique:'.User::class], 'regex:/^\+[1-9]{1}[0-9]{11,14}$/g',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $ref = explode('"',$request['referred_by']);

        $user = User::create([
            'name' => $request->name,
            'role' => 'USER',
            'email' => $request->email,
            'contact' => $request->contact,
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $request->referred_by ? $ref[0] : Null,
            'password' => Hash::make($request->password),
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $userAccount = new UserAccount;
        $userAccount['user_id'] = $user['id'];
        $userAccount['net_amount'] = 0;
        $userAccount->save();

        User::where('id',$user['id'])->update(['email_verified_at'=>date('Y-m-d H:i:s')]);

        // event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
