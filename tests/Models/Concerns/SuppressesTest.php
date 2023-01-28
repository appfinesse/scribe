<?php

namespace Appfinesse\Scribe\Tests\Feature\Models\Concerns;

use Appfinesse\Scribe\Models\Subscription;
use Appfinesse\Scribe\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SuppresssTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public const MODEL = Subscription::class;

    public function testModelReturnsSuppressedWhenSuppressedAtIsOnThePast()
    {
        $model = self::MODEL::factory()->make([
            'suppressed_at' => now()->subDay(),
        ]);

        $this->assertTrue($model->suppressed());
        $this->assertFalse($model->notSuppressed());
    }

    public function testModelReturnsNotSuppressedWhenSuppressedAtIsOnTheFuture()
    {
        $model = self::MODEL::factory()->make([
            'suppressed_at' => now()->addDay(),
        ]);

        $this->assertFalse($model->suppressed());
        $this->assertTrue($model->notSuppressed());
    }

    public function testModelReturnsNotSuppressedWhenSuppressedAtIsNull()
    {
        $model = self::MODEL::factory()->make();
        $model->suppressed_at = null;

        $this->assertFalse($model->suppressed());
        $this->assertTrue($model->notSuppressed());
    }
}
