<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissible\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use ThijsSchalk\LaravelDismissible\Traits\HasDismissibles;

class Dismisser extends Model
{
    use HasDismissibles;
}
