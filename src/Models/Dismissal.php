<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ThijsSchalk\LaravelDismissibles\Database\Factories\DismissalFactory;

class Dismissal extends Model
{
    use HasFactory;

    protected $fillable = [
        'dismissed_until',
        'extra_data',
    ];

    protected $casts = [
        'dismissed_until' => 'immutable_datetime',
        'extra_data'      => 'array',
    ];

    protected static function newFactory(): Factory
    {
        return DismissalFactory::new();
    }

    public function dismissible(): BelongsTo
    {
        return $this->belongsTo(Dismissible::class);
    }

    public function dismisser(): MorphTo
    {
        return $this->morphTo();
    }
}
