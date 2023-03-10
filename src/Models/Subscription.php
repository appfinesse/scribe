<?php

namespace Appfinesse\Scribe\Models;

use Appfinesse\Scribe\Events\SubscriptionCanceled;
use Appfinesse\Scribe\Events\SubscriptionRenewed;
use Appfinesse\Scribe\Events\SubscriptionScheduled;
use Appfinesse\Scribe\Events\SubscriptionStarted;
use Appfinesse\Scribe\Events\SubscriptionSuppressed;
use Appfinesse\Scribe\Models\Concerns\ExpiresAndHasGraceDays;
use Appfinesse\Scribe\Models\Concerns\Starts;
use Appfinesse\Scribe\Models\Concerns\Suppresses;
use Appfinesse\Scribe\Models\Scopes\ExpiringWithGraceDaysScope;
use Appfinesse\Scribe\Models\Scopes\StartingScope;
use Appfinesse\Scribe\Models\Scopes\SuppressingScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use ExpiresAndHasGraceDays;
    use HasFactory;
    use SoftDeletes;
    use Starts;
    use Suppresses;

    protected $dates = [
        'canceled_at',
    ];

    protected $fillable = [
        'canceled_at',
        'expired_at',
        'grace_days_ended_at',
        'started_at',
        'suppressed_at',
        'was_switched',
    ];

    public function plan()
    {
        return $this->belongsTo(config('scribe.models.plan'));
    }

    public function renewals()
    {
        return $this->hasMany(config('scribe.models.subscription_renewal'));
    }

    public function subscriber()
    {
        return $this->morphTo('subscriber');
    }

    public function scopeNotActive(Builder $query)
    {
        return $query->withoutGlobalScopes([
            ExpiringWithGraceDaysScope::class,
            StartingScope::class,
            SuppressingScope::class,
        ])
            ->where(function (Builder $query) {
                $query->where(fn (Builder $query) => $query->onlyExpired())
                    ->orWhere(fn (Builder $query) => $query->onlyNotStarted())
                    ->orWhere(fn (Builder $query) => $query->onlySuppressed());
            });
    }

    public function scopeCanceled(Builder $query)
    {
        return $query->whereNotNull('canceled_at');
    }

    public function scopeNotCanceled(Builder $query)
    {
        return $query->whereNull('canceled_at');
    }

    public function markAsSwitched(): self
    {
        return $this->fill([
            'was_switched' => true,
        ]);
    }

    public function start(?Carbon $startDate = null): self
    {
        $startDate = $startDate ?: today();

        $this->fill(['started_at' => $startDate])
            ->save();

        if ($startDate->isToday()) {
            event(new SubscriptionStarted($this));
        } elseif ($startDate->isFuture()) {
            event(new SubscriptionScheduled($this));
        }

        return $this;
    }

    public function renew(?Carbon $expirationDate = null): self
    {
        $this->renewals()->create([
            'renewal' => true,
            'overdue' => $this->isOverdue,
        ]);

        $expirationDate = $this->getRenewedExpiration($expirationDate);

        $this->update([
            'expired_at' => $expirationDate,
        ]);

        event(new SubscriptionRenewed($this));

        return $this;
    }

    public function cancel(?Carbon $cancelDate = null): self
    {
        $cancelDate = $cancelDate ?: now();

        $this->fill(['canceled_at' => $cancelDate])
            ->save();

        event(new SubscriptionCanceled($this));

        return $this;
    }

    public function suppress(?Carbon $suppressation = null)
    {
        $suppressationDate = $suppressation ?: now();

        $this->fill(['suppressed_at' => $suppressationDate])
            ->save();

        event(new SubscriptionSuppressed($this));

        return $this;
    }

    public function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->grace_days_ended_at ?
                ($this->expired_at->isPast() && $this->grace_days_ended_at->isPast()) :
                $this->expired_at->isPast(),
        );
    }

    private function getRenewedExpiration(?Carbon $expirationDate = null)
    {
        if (! empty($expirationDate)) {
            return $expirationDate;
        }

        if ($this->isOverdue) {
            return $this->plan->calculateNextRecurrenceEnd();
        }

        return $this->plan->calculateNextRecurrenceEnd($this->expired_at);
    }
}
