<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rellix\Dismissibles\Database\Factories\DismissalFactory;

class Dismissal extends Model
{
    use HasFactory;

    protected $fillable = [
        'dismissed_until',
        'extra_data',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'dismissed_until' => 'immutable_datetime',
        'extra_data'      => 'array',
    ];

    protected static function newFactory(): DismissalFactory
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
