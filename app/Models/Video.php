<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'plan_id',
        'title',
        'description',
        'video_url',
        'video_thumbnail',
        'expiry_date',
        'status',
    ];

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class);
    }
}
