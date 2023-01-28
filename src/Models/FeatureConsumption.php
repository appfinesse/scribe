<?php

namespace Appfinesse\Scribe\Models;

use Appfinesse\Scribe\Models\Concerns\Expires;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureConsumption extends Model
{
    use Expires;
    use HasFactory;

    protected $fillable = [
        'consumption',
        'expired_at',
    ];

    public function feature()
    {
        return $this->belongsTo(config('scribe.models.feature'));
    }

    public function subscriber()
    {
        return $this->morphTo('subscriber');
    }
}
