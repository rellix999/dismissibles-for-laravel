<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Concerns;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Rellix\LaravelDismissibles\Contracts\Dismisser;
use Rellix\LaravelDismissibles\Models\Dismissal;
use Rellix\LaravelDismissibles\Models\Dismissible;

class Dismiss
{
    public function __construct(
        public readonly Dismisser $dismisser,
        public readonly Dismissible $dismissible
    ) {
    }

    public function untilTomorrow(?array $extraData = null): void
    {
        $until = Carbon::tomorrow();

        $this->dismiss($until, $extraData);
    }

    public function untilNextWeek(?array $extraData = null): void
    {
        $until = Carbon::now()->addWeek()->startOfWeek();

        $this->dismiss($until, $extraData);
    }

    public function untilNextMonth(?array $extraData = null): void
    {
        $until = Carbon::now()->addMonth()->startOfMonth();

        $this->dismiss($until, $extraData);
    }

    public function untilNextQuarter(?array $extraData = null): void
    {
        $until = Carbon::now()->addQuarter()->startOfQuarter();

        $this->dismiss($until, $extraData);
    }

    public function untilNextYear(?array $extraData = null): void
    {
        $until = Carbon::now()->addYear()->startOfYear();

        $this->dismiss($until, $extraData);
    }

    public function until(DateTimeInterface $dateTime, ?array $extraData = null): void
    {
        $this->dismiss($dateTime, $extraData);
    }

    public function forHours(int $hours, ?array $extraData = null): void
    {
        $until = Carbon::now()->addHours($hours);

        $this->dismiss($until, $extraData);
    }

    public function forDays(int $days, ?array $extraData = null): void
    {
        $until = Carbon::now()->addDays($days);

        $this->dismiss($until, $extraData);
    }

    public function forWeeks(int $weeks, ?array $extraData = null): void
    {
        $until = Carbon::now()->addWeeks($weeks);

        $this->dismiss($until, $extraData);
    }

    public function forMonths(int $months, ?array $extraData = null): void
    {
        $until = Carbon::now()->addMonths($months);

        $this->dismiss($until, $extraData);
    }

    public function forYears(int $years, ?array $extraData = null): void
    {
        $until = Carbon::now()->addYears($years);

        $this->dismiss($until, $extraData);
    }

    public function forever(?array $extraData = null): void
    {
        $this->dismiss(null, $extraData);
    }

    private function dismiss(?DateTimeInterface $until = null, ?array $extraData = null): void
    {
        $dismissal = new Dismissal([
            'dismissed_until' => $until,
            'extra_data'      => $extraData ? json_encode($extraData) : null,
        ]);

        $dismissal->dismisser()->associate($this->dismisser);
        $dismissal->dismissible()->associate($this->dismissible);

        $dismissal->save();
    }
}
