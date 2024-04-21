<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dismissal extends Model
{
    protected $fillable = [
        'dismissed_until',
        'extra_data',
    ];

    protected $casts = [
        'dismissed_until' => 'immutable_datetime',
        'extra_data'      => 'array',
    ];

    public function dismissible(): BelongsTo
    {
        return $this->belongsTo(Dismissible::class);
    }

    public function dismisser(): MorphTo
    {
        return $this->morphTo();
    }
}
