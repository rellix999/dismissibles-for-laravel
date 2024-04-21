<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;

class DismissibleFactory extends Factory
{
    protected $model = Dismissible::class;

    public function definition(): array
    {
        $activeFrom = $this->faker->dateTimeBetween('-3 weeks', 'now');

        return [
            'uuid'         => $this->faker->uuid(),
            'name'         => $this->faker->unique()->text(),
            'active_from'  => $activeFrom,
            'active_until' => $this->faker->optional() ? $this->faker->dateTimeBetween($activeFrom, '+3 weeks') : null,
        ];
    }
}
