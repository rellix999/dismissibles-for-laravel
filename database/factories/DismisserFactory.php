<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ThijsSchalk\LaravelDismissibles\Models\Dismisser;

class DismisserFactory extends Factory
{
    protected $model = Dismisser::class;

    public function definition(): array
    {
        return [];
    }
}
