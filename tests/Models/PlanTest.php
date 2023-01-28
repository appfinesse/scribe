<?php

namespace Appfinesse\Scribe\Tests\Feature\Models;

use Appfinesse\Scribe\Enums\PeriodicityType;
use Appfinesse\Scribe\Models\Plan;
use Appfinesse\Scribe\Models\Subscription;
use Appfinesse\Scribe\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;

class PlanTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testModelCancalculateGraceDaysEnd()
    {
        Carbon::setTestNow(now());

        $days = $this->faker->randomDigitNotNull();
        $graceDays = $this->faker->randomDigitNotNull();
        $plan = Plan::factory()->create([
            'grace_days' => $graceDays,
            'periodicity_type' => PeriodicityType::Day,
            'periodicity' => $days,
        ]);

        $this->assertEquals(
            now()->addDays($days)->addDays($graceDays),
            $plan->calculateGraceDaysEnd($plan->calculateNextRecurrenceEnd()),
        );
    }

    public function testModelCanRetrieveSubscriptions()
    {
        $plan = Plan::factory()
            ->create();

        $subscriptions = Subscription::factory()
            ->for($plan)
            ->count($subscriptionsCount = $this->faker->randomDigitNotNull())
            ->started()
            ->notExpired()
            ->notSuppressed()
            ->create();

        $this->assertEquals($subscriptionsCount, $plan->subscriptions()->count());
        $subscriptions->each(function ($subscription) use ($plan) {
            $this->assertTrue($plan->subscriptions->contains($subscription));
        });
    }
}
