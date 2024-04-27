<?php

declare(strict_types=1);

namespace Rellix\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rellix\LaravelDismissibles\Contracts\Dismisser as DismisserContract;
use Rellix\LaravelDismissibles\Database\Factories\DismisserFactory;
use Rellix\LaravelDismissibles\Traits\HasDismissibles;

class Dismisser extends Model implements DismisserContract
{
    use HasFactory;
    use HasDismissibles;

    protected static function newFactory(): DismisserFactory
    {
        return DismisserFactory::new();
    }
}
