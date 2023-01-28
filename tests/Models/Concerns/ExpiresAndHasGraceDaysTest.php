<?php

namespace Appfinesse\Scribe\Tests\Feature\Models\Concerns;

use Appfinesse\Scribe\Models\Concerns\ExpiresAndHasGraceDays;
use Appfinesse\Scribe\Models\Scopes\ExpiringWithGraceDaysScope;
use Appfinesse\Scribe\Models\Subscription;
use Appfinesse\Scribe\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExpiresAndHasGraceDaysTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public const MODEL = Subscription::class;

    public function testTraitAppliesScope()
    {
        $model = self::MODEL::factory()->create();

        $this->assertArrayHasKey(ExpiresAndHasGraceDays::class, class_uses_recursive($model));
        $this->assertArrayHasKey(ExpiringWithGraceDaysScope::class, $model->getGlobalScopes());
    }

    public function testModelReturnsExpiredStatus()
    {
        $expiredModel = self::MODEL::factory()
            ->expired()
            ->create();

        $expiredModelWithFutureGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->addDay(),
            ]);

        $expiredModelWithPastGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->subDay(),
            ]);

        $notExpiredModel = self::MODEL::factory()
            ->notExpired()
            ->create();

        $this->assertTrue($expiredModel->expired());
        $this->assertFalse($expiredModelWithFutureGraceDays->expired());
        $this->assertTrue($expiredModelWithPastGraceDays->expired());
        $this->assertFalse($notExpiredModel->expired());
    }

    public function testModelReturnsNotExpiredStatus()
    {
        $expiredModel = self::MODEL::factory()
            ->expired()
            ->create();

        $expiredModelWithFutureGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->addDay(),
            ]);

        $expiredModelWithPastGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->subDay(),
            ]);

        $notExpiredModel = self::MODEL::factory()
            ->notExpired()
            ->create();

        $this->assertFalse($expiredModel->notExpired());
        $this->assertTrue($expiredModelWithFutureGraceDays->notExpired());
        $this->assertFalse($expiredModelWithPastGraceDays->notExpired());
        $this->assertTrue($notExpiredModel->notExpired());
    }

    public function testModelReturnsIfItHasExpired()
    {
        $expiredModel = self::MODEL::factory()
            ->expired()
            ->create();

        $expiredModelWithFutureGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->addDay(),
            ]);

        $expiredModelWithPastGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->subDay(),
            ]);

        $notExpiredModel = self::MODEL::factory()
            ->notExpired()
            ->create();

        $this->assertTrue($expiredModel->hasExpired());
        $this->assertFalse($expiredModelWithFutureGraceDays->hasExpired());
        $this->assertTrue($expiredModelWithPastGraceDays->hasExpired());
        $this->assertFalse($notExpiredModel->hasExpired());
    }

    public function testModelReturnsIfItHasNotExpired()
    {
        $expiredModel = self::MODEL::factory()
            ->expired()
            ->create();

        $expiredModelWithFutureGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->addDay(),
            ]);

        $expiredModelWithPastGraceDays = self::MODEL::factory()
            ->expired()
            ->create([
                'grace_days_ended_at' => now()->subDay(),
            ]);

        $notExpiredModel = self::MODEL::factory()
            ->notExpired()
            ->create();

        $this->assertFalse($expiredModel->hasNotExpired());
        $this->assertTrue($expiredModelWithFutureGraceDays->hasNotExpired());
        $this->assertFalse($expiredModelWithPastGraceDays->hasNotExpired());
        $this->assertTrue($notExpiredModel->hasNotExpired());
    }
}
