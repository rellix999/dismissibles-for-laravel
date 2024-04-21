<?php

namespace ThijsSchalk\LaravelDismissible\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ThijsSchalk\LaravelDismissible\Concerns\Dismiss;
use ThijsSchalk\LaravelDismissible\Models\Dismissal;
use ThijsSchalk\LaravelDismissible\Models\Dismissible;

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
            ->where(function (Builder $query) use ($dismissible, $moment) {
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
