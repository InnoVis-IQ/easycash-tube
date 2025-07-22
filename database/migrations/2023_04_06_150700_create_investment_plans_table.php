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
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title',255)->unique();
            $table->text('description')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('daily_profit')->default(0);
            $table->integer('plan_days')->default(0);
            $table->integer('total_profit')->default(0);
            $table->string('status',50)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
