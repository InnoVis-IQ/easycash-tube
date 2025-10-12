<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all unique round date combinations from existing entries
        $existingRounds = DB::table('lucky_withdraw_entries')
            ->select('round_start_date', 'round_end_date')
            ->whereNotNull('round_start_date')
            ->whereNotNull('round_end_date')
            ->distinct()
            ->orderBy('round_start_date', 'asc')
            ->get();

        $roundNumber = 1;
        
        foreach ($existingRounds as $roundData) {
            // Create round record
            $roundId = DB::table('lucky_withdraw_rounds')->insertGetId([
                'round_number' => $roundNumber,
                'start_date' => $roundData->round_start_date,
                'end_date' => $roundData->round_end_date,
                'status' => 'completed', // Mark old rounds as completed by default
                'total_entries' => 0,
                'total_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update all entries with this round date combination
            DB::table('lucky_withdraw_entries')
                ->where('round_start_date', $roundData->round_start_date)
                ->where('round_end_date', $roundData->round_end_date)
                ->update(['round_id' => $roundId]);

            // Check if this round has a winner
            $winner = DB::table('lucky_withdraw_entries')
                ->where('round_id', $roundId)
                ->where('is_winner', true)
                ->first();

            if ($winner) {
                // Update the round with winner information
                DB::table('lucky_withdraw_rounds')
                    ->where('id', $roundId)
                    ->update([
                        'winner_id' => $winner->user_id,
                        'winner_entry_id' => $winner->id,
                        'draw_date' => $winner->updated_at,
                        'status' => 'completed',
                    ]);
            }

            // Update round statistics
            $stats = DB::table('lucky_withdraw_entries')
                ->where('round_id', $roundId)
                ->where('status', 'approved')
                ->where('round_status', 'active')
                ->selectRaw('COUNT(*) as total_entries, COALESCE(SUM(entry_amount), 0) as total_amount')
                ->first();

            DB::table('lucky_withdraw_rounds')
                ->where('id', $roundId)
                ->update([
                    'total_entries' => $stats->total_entries ?? 0,
                    'total_amount' => $stats->total_amount ?? 0,
                ]);

            $roundNumber++;
        }

        // Check if there are any entries without round dates that need to be handled
        $orphanEntries = DB::table('lucky_withdraw_entries')
            ->whereNull('round_id')
            ->count();

        if ($orphanEntries > 0) {
            // Log warning about orphan entries
            \Log::warning("Found {$orphanEntries} lucky withdraw entries without round assignment during migration");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove round_id from entries (set to null)
        DB::table('lucky_withdraw_entries')->update(['round_id' => null]);
        
        // Delete all rounds
        DB::table('lucky_withdraw_rounds')->truncate();
    }
};
