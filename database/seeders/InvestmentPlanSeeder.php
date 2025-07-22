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
                'title' => "PF-1",
                'amount' => "1500",
                'daily_profit' => "105",
                'plan_days' => "60",
                'total_profit' => "6300" ,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-2",
                'amount' => "3000",
                'daily_profit' => "210",
                'plan_days' => "60",
                'total_profit' => "12600",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-3",
                'amount' => "5000",
                'daily_profit' => "350",
                'plan_days' => "60",
                'total_profit' => "21000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-4",
                'amount' => "10000",
                'daily_profit' => "700",
                'plan_days' => "60",
                'total_profit' => "42000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-5",
                'amount' => "15000",
                'daily_profit' => "1050",
                'plan_days' => "60",
                'total_profit' => "63000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-6",
                'amount' => "20000",
                'daily_profit' => "1400",
                'plan_days' => "60",
                'total_profit' => "84000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-7",
                'amount' => "30000",
                'daily_profit' => "2100",
                'plan_days' => "60",
                'total_profit' => "126000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-8",
                'amount' => "50000",
                'daily_profit' => "3500",
                'plan_days' => "60",
                'total_profit' => "210000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-9",
                'amount' => "75000",
                'daily_profit' => "5250",
                'plan_days' => "60",
                'total_profit' => "315000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'title' => "PF-10",
                'amount' => "100000",
                'daily_profit' => "7000",
                'plan_days' => "60",
                'total_profit' => "420000",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
