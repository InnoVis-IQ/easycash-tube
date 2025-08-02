<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'investment_plan_id',
        'earning',
        'expire_at',
        'watched_videos',
        'daily_reward_claimed'
    ];

    protected $casts = [
        'watched_videos' => 'array',
        'expire_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'daily_reward_claimed' => 'date'
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function plan(){
        return $this->belongsTo(InvestmentPlan::class,'investment_plan_id')->withTrashed();
    }
}
