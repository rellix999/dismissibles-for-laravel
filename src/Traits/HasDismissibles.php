<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rellix\Dismissibles\Models\Dismissal;

trait HasDismissibles
{
    public function dismissals(): MorphMany
    {
        return $this->morphMany(Dismissal::class, 'dismisser');
    }
}
