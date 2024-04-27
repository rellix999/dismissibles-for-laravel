<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rellix\LaravelDismissibles\Models\Dismissal;

trait HasDismissibles
{
    public function dismissals(): MorphMany
    {
        return $this->morphMany(Dismissal::class, 'dismisser');
    }
}
