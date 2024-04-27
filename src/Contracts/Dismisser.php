<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Dismisser
{
    public function dismissals(): MorphMany;
}
