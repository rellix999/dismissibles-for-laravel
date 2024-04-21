<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissible\Tests;

use Orchestra\Testbench\TestCase;
use ThijsSchalk\LaravelDismissible\LaravelDismissibleServiceProvider;

abstract class BaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'test'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [LaravelDismissibleServiceProvider::class];
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
