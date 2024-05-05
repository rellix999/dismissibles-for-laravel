<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Rellix\Dismissibles\Contracts\Dismisser;
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

    public function scopeDismissedBy(Builder $query, Dismisser $dismisser): void
    {
        $query
            ->where('dismisser_type', get_class($dismisser))
            ->where('dismisser_id', $dismisser->id);
    }

    public function scopeDismissedAt(Builder $query, ?Carbon $moment = null): void
    {
        if (!$moment) {
            $moment = Carbon::now();
        }

        $query
            ->where('dismissed_until', '>', $moment)
            ->orWhereNull('dismissed_until');
    }
}
