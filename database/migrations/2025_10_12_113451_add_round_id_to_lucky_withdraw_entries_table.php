<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lucky_withdraw_entries', function (Blueprint $table) {
            $table->foreignId('round_id')->nullable()->after('id')->constrained('lucky_withdraw_rounds')->onDelete('cascade');
            $table->index('round_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lucky_withdraw_entries', function (Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropIndex(['round_id']);
            $table->dropColumn('round_id');
        });
    }
};
