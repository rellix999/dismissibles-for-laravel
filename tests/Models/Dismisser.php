<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use ThijsSchalk\LaravelDismissibles\Traits\HasDismissibles;

class Dismisser extends Model
{
    use HasDismissibles;
}
