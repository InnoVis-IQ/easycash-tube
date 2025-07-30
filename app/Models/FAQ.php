<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
} 