<?php

namespace Appfinesse\Scribe\Tests\Feature\Models;

use Appfinesse\Scribe\Models\Feature;
use Appfinesse\Scribe\Models\FeaturePlan;
use Appfinesse\Scribe\Models\Plan;
use Appfinesse\Scribe\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class FeaturePlanTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testModelCanRetrievePlan()
    {
        $feature = Feature::factory()
            ->create();

        $plan = Plan::factory()->create();
        $plan->features()->attach($feature);

        $featurePlanPivot = FeaturePlan::first();

        $this->assertEquals($plan->id, $featurePlanPivot->plan->id);
    }

    public function testModelCanRetrieveFeature()
    {
        $feature = Feature::factory()
            ->create();

        $plan = Plan::factory()->create();
        $plan->features()->attach($feature);

        $featurePlanPivot = FeaturePlan::first();

        $this->assertEquals($feature->id, $featurePlanPivot->feature->id);
    }
}
