<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rellix\Dismissibles\Contracts\Dismisser as DismisserContract;
use Rellix\Dismissibles\Database\Factories\TestDismisserTypeOneFactory;
use Rellix\Dismissibles\Traits\HasDismissibles;

class TestDismisserTypeOne extends Model implements DismisserContract
{
    use HasFactory;
    use HasDismissibles;

    protected static function newFactory(): TestDismisserTypeOneFactory
    {
        return TestDismisserTypeOneFactory::new();
    }
}
