<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Database\Factories\DismissibleFactory;

class Dismissible extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active_from',
        'active_until',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'active_from'  => 'immutable_datetime',
        'active_until' => 'immutable_datetime',
    ];

    protected static function newFactory(): DismissibleFactory
    {
        return DismissibleFactory::new();
    }

    public function scopeActive(Builder $query): void
    {
        $now = Carbon::now();

        $query
            ->where('active_from', '<=', $now)
            ->where(function (Builder $query) use ($now) {
                $query
                    ->where('active_until', '>', $now)
                    ->orWhereNull('active_until');
            });
    }

    public function dismissals(): HasMany
    {
        return $this->hasMany(Dismissal::class);
    }

    public function isDismissedBy(Dismisser $dismisser): bool
    {
        return $this->dismissals()
            ->where('dismisser_id', $dismisser->id)
            ->where(function (Builder $query) {
                $query
                    ->where('dismissed_until', '>', Carbon::now())
                    ->orWhereNull('dismissed_until');
            })
            ->exists();
    }
}
