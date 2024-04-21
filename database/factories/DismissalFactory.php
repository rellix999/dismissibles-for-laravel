<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;

class DismissalFactory extends Factory
{
    protected $model = Dismissal::class;

    public function definition(): array
    {
        // TODO: Check active date of dismissible

        return [
            'dismissed_until' => $this->faker->optional() ? $this->faker->dateTimeBetween('-3 months', '+3 months') : null,
            'extra_data'      => $this->faker->optional() ? ['some_extra_data' => $this->faker->randomNumber()] : null,
        ];
    }
}
