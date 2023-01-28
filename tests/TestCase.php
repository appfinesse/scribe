<?php

namespace Appfinesse\Scribe\Tests;

use Appfinesse\Scribe\ScribeServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Appfinesse\\Scribe\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ScribeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_scribe_tables.php.stub';
        $migration->up();
    }
}
