<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use ThijsSchalk\LaravelDismissibles\Models\Dismissal;

trait HasDismissibles
{
    public function dismissals(): MorphMany
    {
        return $this->morphMany(Dismissal::class, 'dismisser');
    }
}
