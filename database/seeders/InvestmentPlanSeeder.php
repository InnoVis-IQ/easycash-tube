<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('investment_plans')->insert([
            [
                'title' => "Plan 1",
                'description' => "Earn 10% daily by watching videos. Duration: 15 days. No withdrawal allowed.",
                'amount' => "500",
                'daily_profit' => "50", // 10% of 500
                'plan_days' => "15",
                'total_profit' => "750", // 50 * 15 days
                'buffer_days' => "0",
                'limit' => "no_withdrawal",
                'status' => "active",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "Plan 2",
                'description' => "Earn 10% daily. Duration: 15 days. Daily withdrawal allowed.",
                'amount' => "2000",
                'daily_profit' => "200", // 10% of 2000
                'plan_days' => "15",
                'total_profit' => "3000", // 200 * 15 days
                'buffer_days' => "0",
                'limit' => "daily_withdrawal",
                'status' => "active",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "Plan 3",
                'description' => "Earn 10% daily. Duration: 15 days. Daily withdrawal allowed.",
                'amount' => "7000",
                'daily_profit' => "700", // 10% of 7000
                'plan_days' => "15",
                'total_profit' => "10500", // 700 * 15 days
                'buffer_days' => "0",
                'limit' => "daily_withdrawal",
                'status' => "active",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "Plan 4",
                'description' => "Earn 5% daily. Duration: 20 days. Daily withdrawal allowed.",
                'amount' => "24000",
                'daily_profit' => "1200", // 5% of 24000
                'plan_days' => "20",
                'total_profit' => "24000", // 1200 * 20 days
                'buffer_days' => "0",
                'limit' => "daily_withdrawal",
                'status' => "active",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "Plan 5",
                'description' => "Earn 5% daily. Duration: 20 days. Daily withdrawal allowed.",
                'amount' => "50000",
                'daily_profit' => "2500", // 5% of 50000
                'plan_days' => "20",
                'total_profit' => "50000", // 2500 * 20 days
                'buffer_days' => "0",
                'limit' => "daily_withdrawal",
                'status' => "active",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
