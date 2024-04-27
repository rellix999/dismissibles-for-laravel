<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Dismisser
{
    public function dismissals(): MorphMany;
}
