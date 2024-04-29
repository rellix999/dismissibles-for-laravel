<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Dismisser
{
    public function dismissals(): MorphMany;
}
