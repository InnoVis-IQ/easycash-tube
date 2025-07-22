<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }

    public function bank(){
        return $this->belongsTo(BankAccount::class,'bank_account_id')->withTrashed();
    }

    public function user_bank(){
        return $this->belongsTo(UserBankAccount::class,'user_bank_account_id')->withTrashed();
    }

}
