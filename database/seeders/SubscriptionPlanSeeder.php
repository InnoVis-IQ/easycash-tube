<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'plan_name' => 'Basic',
                'price' => 19.99,
                'daily_earning' => 2.50,
                'duration' => 30,
                'limit' => 100,
            ],
            [
                'plan_name' => 'Premium',
                'price' => 49.99,
                'daily_earning' => 8.00,
                'duration' => 60,
                'limit' => 500,
            ],
            [
                'plan_name' => 'Pro',
                'price' => 99.99,
                'daily_earning' => 15.00,
                'duration' => 90,
                'limit' => 1000,
            ],
            [
                'plan_name' => 'Enterprise',
                'price' => 199.99,
                'daily_earning' => 35.00,
                'duration' => 180,
                'limit' => 5000,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
} 