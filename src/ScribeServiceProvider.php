<?php

namespace Appfinesse\Scribe;

use Appfinesse\Scribe\Commands\ScribeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScribeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scribe')
            ->hasConfigFile('scribe')
            ->hasViews()
            ->hasMigration('create_scribe_tables')
            ->hasCommand(ScribeCommand::class);

    }
}
