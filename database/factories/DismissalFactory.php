<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Models\Dismisser;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;

class DismissalFactory extends Factory
{
    protected $model = Dismissal::class;

    public function definition(): array
    {
        return [
            'dismissible_id'  => fn () => Dismissible::factory()->create(),
            'dismissed_until' => null,
            'extra_data'      => $this->faker->optional() ? ['some_extra_data' => $this->faker->randomNumber()] : null,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Dismissal $dismissal) {
            if ($this->faker->optional()) {
                $this->setDismissedUntilByDismissible($dismissal);
            }

            if (!$dismissal->dismisser) {
                $dismisser = Dismisser::factory()->create();

                $dismissal->dismisser()->associate($dismisser);
            }
        });
    }

    private function setDismissedUntilByDismissible(Dismissal $dismissal): void
    {
        $dismissible = $dismissal->dismissible;

        $dismissal->dismissed_until = $this->faker->dateTimeBetween($dismissible->active_from, $dismissible->active_until);
    }
}
