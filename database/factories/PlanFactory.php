<?php

namespace Appfinesse\Scribe\Database\Factories;

use Appfinesse\Scribe\Enums\PeriodicityType;
use Appfinesse\Scribe\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'grace_days' => 0,
            'name' => $this->faker->words(asText: true),
            'periodicity' => $this->faker->randomDigitNotNull(),
            'periodicity_type' => $this->faker->randomElement([
                PeriodicityType::Year,
                PeriodicityType::Month,
                PeriodicityType::Week,
                PeriodicityType::Day,
            ]),
        ];
    }

    public function withGraceDays()
    {
        return $this->state([
            'grace_days' => $this->faker->randomDigitNotNull(),
        ]);
    }
}
