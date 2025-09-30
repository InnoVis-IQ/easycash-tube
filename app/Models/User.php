<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'contact',
        'email',
        'password',
        'referral_code',
        'referred_by',
        'bonus_given',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userAccount(){
        return $this->hasOne(UserAccount::class,'user_id');
    }

    public function subscriptions(){
        return $this->hasMany(UserSubscription::class, 'user_id');
    }

    public function withdrawals(){
        return $this->hasMany(Withdraw::class, 'user_id');
    }

    public function deposits(){
        return $this->hasMany(Deposit::class, 'user_id');
    }

    public function userBankAccounts(){
        return $this->hasMany(UserBankAccount::class, 'user_id');
    }
}
