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
        'round_id',
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
            
            // Assign to active round if not set
            if (empty($model->round_id)) {
                $activeRound = LuckyWithdrawRound::getActiveRound();
                if ($activeRound) {
                    $model->round_id = $activeRound->id;
                    // Keep legacy date fields for backward compatibility
                    $model->round_start_date = $activeRound->start_date;
                    $model->round_end_date = $activeRound->end_date;
                }
            }
        });

        static::created(function ($model) {
            // Update round statistics when a new entry is created
            if ($model->round) {
                $model->round->updateStatistics();
            }
        });

        static::updated(function ($model) {
            // Update round statistics when entry status changes
            if ($model->isDirty('status') || $model->isDirty('round_status')) {
                if ($model->round) {
                    $model->round->updateStatistics();
                }
            }
        });

        static::deleted(function ($model) {
            // Update round statistics when an entry is deleted
            if ($model->round) {
                $model->round->updateStatistics();
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
     * Get the round this entry belongs to
     */
    public function round()
    {
        return $this->belongsTo(LuckyWithdrawRound::class, 'round_id');
    }

    /**
     * Get current round start date from database (legacy method for backward compatibility)
     * @deprecated Use LuckyWithdrawRound::getActiveRound() instead
     */
    public static function getCurrentRoundStartDate()
    {
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if ($activeRound) {
            return $activeRound->start_date->format('Y-m-d');
        }
        
        // Fallback to old logic if no active round exists
        $today = now()->format('Y-m-d');
        
        $currentRound = self::where('round_start_date', '<=', $today)
                           ->where('round_end_date', '>=', $today)
                           ->orderBy('round_start_date', 'desc')
                           ->first();
        
        if ($currentRound) {
            return $currentRound->round_start_date->format('Y-m-d');
        }
        
        $latestRound = self::orderBy('round_start_date', 'desc')
                          ->first();
        
        if ($latestRound) {
            return $latestRound->round_start_date->format('Y-m-d');
        }
        
        $daysSinceStart = now()->diffInDays('2024-01-01') % 15;
        return now()->subDays($daysSinceStart)->format('Y-m-d');
    }

    /**
     * Get current round end date from database (legacy method for backward compatibility)
     * @deprecated Use LuckyWithdrawRound::getActiveRound() instead
     */
    public static function getCurrentRoundEndDate()
    {
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if ($activeRound) {
            return $activeRound->end_date->format('Y-m-d');
        }
        
        // Fallback to old logic
        $latestEntry = self::where('round_start_date', self::getCurrentRoundStartDate())
                           ->orderBy('round_end_date', 'desc')
                           ->first();
        
        if ($latestEntry && $latestEntry->round_end_date) {
            return $latestEntry->round_end_date->format('Y-m-d');
        }
        
        $startDate = self::getCurrentRoundStartDate();
        return date('Y-m-d', strtotime($startDate . ' +14 days'));
    }

    /**
     * Check if current round is active (legacy method for backward compatibility)
     * @deprecated Use LuckyWithdrawRound::getActiveRound() instead
     */
    public static function isCurrentRoundActive()
    {
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if ($activeRound) {
            return $activeRound->isInDateRange();
        }
        
        $today = now()->format('Y-m-d');
        $endDate = self::getCurrentRoundEndDate();
        return $today <= $endDate;
    }

    /**
     * Get active entries for current round
     */
    public static function getActiveEntriesForCurrentRound()
    {
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if ($activeRound) {
            return $activeRound->approvedEntries()->get();
        }
        
        // Fallback to old logic
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
        $activeRound = LuckyWithdrawRound::getActiveRound();
        
        if ($activeRound) {
            return $query->where('round_id', $activeRound->id);
        }
        
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
