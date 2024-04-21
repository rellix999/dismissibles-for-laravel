<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LaravelDismissiblesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if (App::environment() === 'testing') {
            $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
        }
    }
}
