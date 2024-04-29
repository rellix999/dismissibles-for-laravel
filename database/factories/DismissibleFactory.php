<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Database\Factories;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Rellix\Dismissibles\Models\Dismissible;

class DismissibleFactory extends Factory
{
    protected $model = Dismissible::class;

    public function definition(): array
    {
        $activeFrom = $this->faker->dateTimeBetween('-3 weeks', 'now');

        return [
            'name'         => $this->faker->unique()->text(),
            'active_from'  => $activeFrom,
            'active_until' => $this->faker->optional() ? $this->faker->dateTimeBetween($activeFrom, '+3 weeks') : null,
        ];
    }

    public function active(?CarbonPeriod $period = null): Factory
    {
        if ($period === null) {
            $activeFrom = $this->faker->dateTimeBetween('-4 weeks', '-1 week');
            $activeUntil = $this->faker->optional() ? $this->faker->dateTimeBetween('+1 week', '+4 weeks') : null;

            $period = CarbonPeriod::create($activeFrom, $activeUntil);
        }

        return $this->state(function (array $attributes) use ($period) {
            return [
                'active_from'  => $period->start,
                'active_until' => $period->end,
            ];
        });
    }
}
