<?php

declare(strict_types=1);

namespace Rellix\Dismissibles;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Rellix\Dismissibles\Facades\Dismissibles;

class DismissiblesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if (App::environment() === 'testing') {
            $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
        }
    }

    public function register(): void
    {
        $this->app->bind('dismissibles', function ($app) {
            return new Dismissibles();
        });
    }
}
