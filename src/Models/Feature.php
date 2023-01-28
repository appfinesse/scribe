<?php

namespace Appfinesse\Scribe\Models;

use Appfinesse\Scribe\Models\Concerns\HandlesRecurrence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HandlesRecurrence;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'consumable',
        'name',
        'periodicity_type',
        'periodicity',
        'quota',
        'postpaid',
    ];

    public function plans()
    {
        return $this->belongsToMany(config('scribe.models.plan'))
            ->using(config('scribe.models.feature_plan'));
    }

    public function tickets()
    {
        return $this->hasMany(config('scribe.models.feature_ticket'));
    }
}
