<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissible;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LaravelDismissibleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (App::environment() === 'testing') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
        }

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ]);
    }
}
