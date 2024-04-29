<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Tests;

use Orchestra\Testbench\TestCase;
use Rellix\Dismissibles\DismissiblesServiceProvider;

abstract class BaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'test'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [DismissiblesServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'test');

        $app['config']->set('database.connections.test', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
