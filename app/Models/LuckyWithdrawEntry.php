<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LuckyWithdrawEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entry_code',
        'entry_amount',
        'payment_method',
        'transaction_id',
        'status',
        'round_status',
        'round_start_date',
        'round_end_date',
        'is_winner',
        'admin_notes'
    ];

    protected $casts = [
        'entry_amount' => 'decimal:2',
        'round_start_date' => 'date',
        'round_end_date' => 'date',
        'is_winner' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->entry_code)) {
                $model->entry_code = 'LW' . strtoupper(Str::random(8));
            }
            
            // Set round dates if not provided
            if (empty($model->round_start_date)) {
                $model->round_start_date = self::getCurrentRoundStartDate();
            }
            if (empty($model->round_end_date)) {
                $model->round_end_date = self::getCurrentRoundEndDate();
            }
        });
    }

    /**
     * Get the user that owns the entry
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get current round start date (every 15 days)
     */
    public static function getCurrentRoundStartDate()
    {
        $today = now();
        $daysSinceStart = $today->diffInDays('2024-01-01') % 15;
        return $today->subDays($daysSinceStart)->format('Y-m-d');
    }

    /**
     * Get current round end date
     */
    public static function getCurrentRoundEndDate()
    {
        $startDate = self::getCurrentRoundStartDate();
        return date('Y-m-d', strtotime($startDate . ' +14 days'));
    }

    /**
     * Check if current round is active
     */
    public static function isCurrentRoundActive()
    {
        $today = now()->format('Y-m-d');
        $endDate = self::getCurrentRoundEndDate();
        return $today <= $endDate;
    }

    /**
     * Get active entries for current round
     */
    public static function getActiveEntriesForCurrentRound()
    {
        return self::where('round_start_date', self::getCurrentRoundStartDate())
                   ->where('status', 'approved')
                   ->where('round_status', 'active')
                   ->get();
    }

    /**
     * Scope for current round entries
     */
    public function scopeCurrentRound($query)
    {
        return $query->where('round_start_date', self::getCurrentRoundStartDate());
    }

    /**
     * Scope for approved entries
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for active round
     */
    public function scopeActiveRound($query)
    {
        return $query->where('round_status', 'active');
    }
}
