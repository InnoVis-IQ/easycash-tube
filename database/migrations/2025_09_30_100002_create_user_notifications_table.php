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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['plan_expiry', 'new_offer', 'deposit_approved', 'withdrawal_processed', 'referral_bonus', 'lucky_winner', 'system_alert'])->default('system_alert');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like plan details, amounts, etc.
            $table->boolean('is_read')->default(false);
            $table->boolean('is_important')->default(false); // For critical notifications
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Auto-expire notifications
            $table->timestamps();
            
            // Index for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
