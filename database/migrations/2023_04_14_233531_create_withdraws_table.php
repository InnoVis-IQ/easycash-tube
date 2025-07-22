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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('amount')->default(0);
            $table->foreignId('bank_account_id')->nullable()->references('id')->on('bank_accounts');
            $table->foreignId('user_bank_account_id')->nullable()->references('id')->on('user_bank_accounts');
            $table->tinyText('picture_proof')->nullable();
            $table->string('status',50)->default('requested');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
