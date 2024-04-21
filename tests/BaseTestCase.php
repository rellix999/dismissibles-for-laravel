<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests;

use Orchestra\Testbench\TestCase;
use ThijsSchalk\LaravelDismissibles\LaravelDismissiblesServiceProvider;

abstract class BaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'test'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [LaravelDismissiblesServiceProvider::class];
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
