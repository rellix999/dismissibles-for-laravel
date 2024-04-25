<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ThijsSchalk\LaravelDismissibles\Concerns\Dismiss;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;
use ThijsSchalk\LaravelDismissibles\Models\Dismissible;

trait HasDismissibles
{
    public function dismissals(): MorphMany
    {
        return $this->morphMany(Dismissal::class, 'dismisser');
    }

    public function hasDismissed(Dismissible $dismissible, ?DateTimeInterface $moment = null): bool
    {
        $moment = $moment ?? Carbon::now();

        return $this->dismissals()
            ->where('dismissible_id', $dismissible->id)
            ->where(function (Builder $query) use ($moment) {
                $query
                    ->where('dismissed_until', '>', $moment)
                    ->orWhereNull('dismissed_until');
            })
            ->exists();
    }

    public function dismiss(Dismissible $dismissible): Dismiss
    {
        return new Dismiss($this, $dismissible);
    }
}
