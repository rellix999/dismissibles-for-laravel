<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles;

use Illuminate\Support\ServiceProvider;
use Rellix\LaravelDismissibles\Facades\Dismissibles;

class LaravelDismissiblesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        $this->app->bind('dismissibles', function ($app) {
            return new Dismissibles();
        });
    }
}
