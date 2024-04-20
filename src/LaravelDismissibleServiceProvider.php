<?php

namespace ThijsSchalk\LaravelDismissible;

use Illuminate\Support\ServiceProvider;

class LaravelDismissibleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ]);
    }
}
