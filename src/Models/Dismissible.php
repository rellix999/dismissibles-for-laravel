<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Database\Factories\DismissibleFactory;

/**
 * @property Carbon $active_from
 * @property Carbon $active_until
 */
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

    public function dismissals(): HasMany
    {
        return $this->hasMany(Dismissal::class);
    }

    public function activePeriod(): CarbonPeriod
    {
        return CarbonPeriod::create($this->active_from, $this->active_until);
    }

    public function isDismissedBy(Dismisser $dismisser, ?Carbon $moment = null): bool
    {
        if (!$moment) {
            $moment = Carbon::now();
        }

        return $this->dismissals()
            ->dismissedBy($dismisser)
            ->dismissedAt($moment)
            ->exists();
    }

    public function scopeActive(Builder $query, ?Carbon $moment = null): void
    {
        if (!$moment) {
            $moment = Carbon::now();
        }

        $query
            ->where('active_from', '<=', $moment)
            ->where(function (Builder $query) use ($moment) {
                $query
                    ->where('active_until', '>', $moment)
                    ->orWhereNull('active_until');
            });
    }

    public function scopeNotDismissedBy(Builder $query, Dismisser $dismisser, ?Carbon $moment = null): void
    {
        if (!$moment) {
            $moment = Carbon::now();
        }

        $query->whereDoesntHave('dismissals', function (Builder $query) use ($dismisser, $moment) {
            $query->dismissedBy($dismisser)->dismissedAt($moment);
        });
    }
}
