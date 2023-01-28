<?php

namespace Appfinesse\Scribe\Models;

use Appfinesse\Scribe\Models\Concerns\HandlesRecurrence;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Plan extends Model
{
    use HandlesRecurrence;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'grace_days',
        'name',
        'periodicity_type',
        'periodicity',
    ];

    public function features()
    {
        return $this->belongsToMany(config('scribe.models.feature'))
            ->using(config('scribe.models.feature_plan'))
            ->withPivot(['charges']);
    }

    public function subscriptions()
    {
        return $this->hasMany(config('scribe.models.subscription'));
    }

    public function calculateGraceDaysEnd(Carbon $recurrenceEnd)
    {
        return $recurrenceEnd->copy()->addDays($this->grace_days);
    }

    public function hasGraceDays(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->grace_days),
        );
    }
}
