<?php

namespace Appfinesse\Scribe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Appfinesse\Scribe\Scribe
 */
class Scribe extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Appfinesse\Scribe\Scribe::class;
    }
}
