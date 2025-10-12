<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LuckyWithdrawRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_number',
        'start_date',
        'end_date',
        'status',
        'winner_id',
        'winner_entry_id',
        'total_entries',
        'total_amount',
        'draw_date',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'draw_date' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->round_number)) {
                $lastRound = self::orderBy('round_number', 'desc')->first();
                $model->round_number = $lastRound ? $lastRound->round_number + 1 : 1;
            }
        });
    }

    /**
     * Get the winner user
     */
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Get the winning entry
     */
    public function winnerEntry()
    {
        return $this->belongsTo(LuckyWithdrawEntry::class, 'winner_entry_id');
    }

    /**
     * Get all entries for this round
     */
    public function entries()
    {
        return $this->hasMany(LuckyWithdrawEntry::class, 'round_id');
    }

    /**
     * Get approved entries for this round
     */
    public function approvedEntries()
    {
        return $this->hasMany(LuckyWithdrawEntry::class, 'round_id')
                    ->where('status', 'approved')
                    ->where('round_status', 'active');
    }

    /**
     * Get the current active round
     */
    public static function getActiveRound()
    {
        return self::where('status', 'active')->first();
    }

    /**
     * Check if this round is currently active (within date range)
     */
    public function isInDateRange()
    {
        $today = now()->format('Y-m-d');
        return $today >= $this->start_date->format('Y-m-d') 
            && $today <= $this->end_date->format('Y-m-d');
    }

    /**
     * Check if this round has expired
     */
    public function hasExpired()
    {
        return now()->format('Y-m-d') > $this->end_date->format('Y-m-d');
    }

    /**
     * Update the round statistics
     */
    public function updateStatistics()
    {
        $entries = $this->entries()
                        ->where('status', 'approved')
                        ->where('round_status', 'active')
                        ->get();

        $this->update([
            'total_entries' => $entries->count(),
            'total_amount' => $entries->sum('entry_amount')
        ]);
    }

    /**
     * Select a winner for this round
     */
    public function selectWinner()
    {
        $entries = $this->approvedEntries()->get();

        if ($entries->isEmpty()) {
            return null;
        }

        // Random selection
        $winner = $entries->random();

        $this->update([
            'winner_id' => $winner->user_id,
            'winner_entry_id' => $winner->id,
            'draw_date' => now(),
            'status' => 'completed'
        ]);

        // Update the winning entry
        $winner->update(['is_winner' => true]);

        // Update all other entries to completed status
        $this->entries()
             ->where('id', '!=', $winner->id)
             ->where('round_status', 'active')
             ->update(['round_status' => 'completed']);

        return $winner;
    }

    /**
     * Complete this round with a specific winner
     */
    public function completeWithWinner($entryId)
    {
        $entry = $this->entries()->where('id', $entryId)->first();

        if (!$entry) {
            return false;
        }

        $this->update([
            'winner_id' => $entry->user_id,
            'winner_entry_id' => $entry->id,
            'draw_date' => now(),
            'status' => 'completed'
        ]);

        // Update the winning entry
        $entry->update(['is_winner' => true]);

        // Update all other entries to completed status
        $this->entries()
             ->where('id', '!=', $entry->id)
             ->where('round_status', 'active')
             ->update(['round_status' => 'completed']);

        return true;
    }

    /**
     * Cancel this round
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason
        ]);

        // Update all entries to cancelled status
        $this->entries()
             ->where('round_status', 'active')
             ->update(['round_status' => 'cancelled']);
    }

    /**
     * Scope for active rounds
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed rounds
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute()
    {
        return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute()
    {
        if ($this->status !== 'active') {
            return 0;
        }

        $today = now()->startOfDay();
        $endDate = $this->end_date->startOfDay();
        
        $days = $today->diffInDays($endDate, false);
        
        return max(0, $days);
    }
}
