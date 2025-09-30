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
        Schema::create('lucky_withdraw_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('entry_code')->unique(); // Unique entry code for each participant
            $table->decimal('entry_amount', 10, 2)->default(100.00); // Entry fee (Rs. 100)
            $table->enum('payment_method', ['easypaisa', 'jazzcash', 'bank_transfer'])->default('easypaisa');
            $table->string('transaction_id')->nullable(); // Payment transaction ID
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('round_status', ['active', 'completed', 'winner_selected'])->default('active');
            $table->date('round_start_date'); // Start date of the current round (every 15 days)
            $table->date('round_end_date'); // End date of the current round
            $table->boolean('is_winner')->default(false);
            $table->text('admin_notes')->nullable(); // Admin notes for approval/rejection
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lucky_withdraw_entries');
    }
};
