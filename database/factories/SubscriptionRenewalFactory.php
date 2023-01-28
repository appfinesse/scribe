<?php

namespace Appfinesse\Scribe\Database\Factories;

use Appfinesse\Scribe\Models\Subscription;
use Appfinesse\Scribe\Models\SubscriptionRenewal;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionRenewalFactory extends Factory
{
    protected $model = SubscriptionRenewal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subscription_id' => Subscription::factory(),
            'overdue' => $this->faker->boolean(),
            'renewal' => $this->faker->boolean(),
        ];
    }
}
