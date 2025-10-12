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
        Schema::create('lucky_withdraw_rounds', function (Blueprint $table) {
            $table->id();
            $table->integer('round_number')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('winner_entry_id')->nullable()->constrained('lucky_withdraw_entries')->onDelete('set null');
            $table->integer('total_entries')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->dateTime('draw_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lucky_withdraw_rounds');
    }
};
