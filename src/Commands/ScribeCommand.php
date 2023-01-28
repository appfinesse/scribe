<?php

namespace Appfinesse\Scribe\Commands;

use Illuminate\Console\Command;

class ScribeCommand extends Command
{
    public $signature = 'scribe';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
