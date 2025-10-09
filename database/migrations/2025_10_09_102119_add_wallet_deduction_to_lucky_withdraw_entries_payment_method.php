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
        // Modify the payment_method enum to include 'wallet_deduction'
        DB::statement("ALTER TABLE lucky_withdraw_entries MODIFY COLUMN payment_method ENUM('easypaisa', 'jazzcash', 'bank_transfer', 'wallet_deduction') DEFAULT 'easypaisa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'wallet_deduction' from the enum
        DB::statement("ALTER TABLE lucky_withdraw_entries MODIFY COLUMN payment_method ENUM('easypaisa', 'jazzcash', 'bank_transfer') DEFAULT 'easypaisa'");
    }
};
