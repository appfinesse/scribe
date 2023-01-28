<?php

namespace Appfinesse\Scribe\Tests\Feature\Models\Concerns;

use Appfinesse\Scribe\Models\Subscription;
use Appfinesse\Scribe\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class StartsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public const MODEL = Subscription::class;

    public function testModelReturnsStartedWhenStartedAtIsOnThePast()
    {
        $model = self::MODEL::factory()->make([
            'started_at' => now()->subDay(),
        ]);

        $this->assertTrue($model->started());
        $this->assertFalse($model->notStarted());
    }

    public function testModelReturnsNotStartedWhenStartedAtIsOnTheFuture()
    {
        $model = self::MODEL::factory()->make([
            'started_at' => now()->addDay(),
        ]);

        $this->assertFalse($model->started());
        $this->assertTrue($model->notStarted());
    }

    public function testModelReturnsNotStartedWhenStartedAtIsNull()
    {
        $model = self::MODEL::factory()->make();
        $model->started_at = null;

        $this->assertFalse($model->started());
        $this->assertTrue($model->notStarted());
    }
}
