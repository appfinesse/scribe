<?php

namespace Appfinesse\Scribe\Tests\Mocks\Database\Factories;

use Appfinesse\Scribe\Tests\Mocks\Models\User;
use Orchestra\Testbench\Factories\UserFactory as OrchestraUserFactory;

class UserFactory extends OrchestraUserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;
}
