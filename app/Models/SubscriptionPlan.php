<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'price',
        'daily_earning',
        'duration',
        'limit',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'daily_earning' => 'decimal:2',
        'duration' => 'integer',
        'limit' => 'integer',
    ];
} 