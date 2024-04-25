<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ThijsSchalk\LaravelDismissibles\Database\Factories\DismisserFactory;
use ThijsSchalk\LaravelDismissibles\Traits\HasDismissibles;

class Dismisser extends Model
{
    use HasFactory;
    use HasDismissibles;

    protected static function newFactory(): DismisserFactory
    {
        return DismisserFactory::new();
    }
}
