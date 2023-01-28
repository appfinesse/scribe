<?php

namespace Appfinesse\Scribe\Models;

use Appfinesse\Scribe\Models\Concerns\Expires;
use Illuminate\Database\Eloquent\Model;

class FeatureTicket extends Model
{
    use Expires;

    protected $fillable = [
        'charges',
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
