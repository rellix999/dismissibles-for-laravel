<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rellix\Dismissibles\Contracts\Dismisser as DismisserContract;
use Rellix\Dismissibles\Database\Factories\TestDismisserOneFactory;
use Rellix\Dismissibles\Traits\HasDismissibles;

class TestDismisserOne extends Model implements DismisserContract
{
    use HasFactory;
    use HasDismissibles;

    protected static function newFactory(): TestDismisserOneFactory
    {
        return TestDismisserOneFactory::new();
    }
}
