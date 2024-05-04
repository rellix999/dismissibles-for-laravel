<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rellix\Dismissibles\Models\TestDismisserTypeOne;

class TestDismisserTypeOneFactory extends Factory
{
    protected $model = TestDismisserTypeOne::class;

    public function definition(): array
    {
        return [];
    }
}
