<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_subscription_plans_index(): void
    {
        $user = User::factory()->create();
        $plans = SubscriptionPlan::factory(3)->create();

        $response = $this->actingAs($user)
            ->get(route('subscription-plans.index'));

        $response->assertStatus(200);
        $response->assertViewIs('subscription-plans.index');
        $response->assertViewHas('subscriptionPlans');
    }

    public function test_user_can_create_subscription_plan(): void
    {
        $user = User::factory()->create();

        $planData = [
            'plan_name' => 'Test Plan',
            'price' => 29.99,
            'daily_earning' => 5.00,
            'duration' => 30,
            'limit' => 200,
        ];

        $response = $this->actingAs($user)
            ->post(route('subscription-plans.store'), $planData);

        $response->assertRedirect(route('subscription-plans.index'));
        $this->assertDatabaseHas('subscription_plans', $planData);
    }

    public function test_user_can_view_subscription_plan(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('subscription-plans.show', $plan));

        $response->assertStatus(200);
        $response->assertViewIs('subscription-plans.show');
        $response->assertViewHas('subscriptionPlan', $plan);
    }

    public function test_user_can_update_subscription_plan(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $updatedData = [
            'plan_name' => 'Updated Plan',
            'price' => 39.99,
            'daily_earning' => 7.50,
            'duration' => 60,
            'limit' => 300,
        ];

        $response = $this->actingAs($user)
            ->put(route('subscription-plans.update', $plan), $updatedData);

        $response->assertRedirect(route('subscription-plans.index'));
        $this->assertDatabaseHas('subscription_plans', $updatedData);
    }

    public function test_user_can_delete_subscription_plan(): void
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('subscription-plans.destroy', $plan));

        $response->assertRedirect(route('subscription-plans.index'));
        $this->assertDatabaseMissing('subscription_plans', ['id' => $plan->id]);
    }

    public function test_validation_works_for_creating_plan(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('subscription-plans.store'), []);

        $response->assertSessionHasErrors(['plan_name', 'price', 'daily_earning', 'duration', 'limit']);
    }
} 