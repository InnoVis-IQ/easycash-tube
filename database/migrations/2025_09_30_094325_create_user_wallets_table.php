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
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('wallet_type', ['main', 'earn', 'bonus'])->default('main');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('total_deposited', 15, 2)->default(0.00); // Total amount deposited into this wallet
            $table->decimal('total_withdrawn', 15, 2)->default(0.00); // Total amount withdrawn from this wallet
            $table->decimal('total_earned', 15, 2)->default(0.00); // Total amount earned in this wallet
            $table->boolean('is_withdrawable')->default(true); // Whether funds can be withdrawn
            $table->boolean('can_buy_plans')->default(true); // Whether funds can be used to buy plans
            $table->text('description')->nullable(); // Description of wallet type
            $table->timestamps();
            
            // Ensure one wallet per type per user
            $table->unique(['user_id', 'wallet_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
