<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_name' => $this->faker->randomElement(['Basic', 'Premium', 'Pro', 'Enterprise', 'Starter', 'Advanced']),
            'price' => $this->faker->randomFloat(2, 9.99, 299.99),
            'daily_earning' => $this->faker->randomFloat(2, 1.00, 50.00),
            'duration' => $this->faker->randomElement([30, 60, 90, 180, 365]),
            'limit' => $this->faker->randomElement([100, 500, 1000, 5000, 10000]),
        ];
    }
} 