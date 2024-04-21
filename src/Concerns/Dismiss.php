<?php

namespace ThijsSchalk\LaravelDismissible\Concerns;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ThijsSchalk\LaravelDismissible\Models\Dismissal;
use ThijsSchalk\LaravelDismissible\Models\Dismissible;

class Dismiss
{
    public function __construct(
        private readonly Model $dismisser,
        private readonly Dismissible $dismissible
    ) {
    }

    public function forToday(?array $extraData = null): void
    {
        $until = Carbon::now()->endOfDay();

        $this->dismiss($until, $extraData);
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

    public function forThisWeek(?array $extraData = null): void
    {
        $until = Carbon::now()->endOfWeek();

        $this->dismiss($until, $extraData);
    }

    public function forThisMonth(?array $extraData = null): void
    {
        $until = Carbon::now()->endOfMonth();

        $this->dismiss($until, $extraData);
    }

    public function forThisQuarter(?array $extraData = null): void
    {
        $until = Carbon::now()->endOfQuarter();

        $this->dismiss($until, $extraData);
    }

    public function forThisYear(?array $extraData = null): void
    {
        $until = Carbon::now()->endOfYear();

        $this->dismiss($until, $extraData);
    }

    public function until(DateTimeInterface $dateTime, ?array $extraData = null): void
    {
        $this->dismiss($dateTime, $extraData);
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
