<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ThijsSchalk\LaravelDismissibles\Database\Factories\DismissibleFactory;

class Dismissible extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active_from',
        'active_until',
    ];

    protected $casts = [
        'active_from'  => 'immutable_datetime',
        'active_until' => 'immutable_datetime',
    ];

    protected static function newFactory(): Factory
    {
        return DismissibleFactory::new();
    }

    public function scopeActive(Builder $query): void
    {
        $now = Carbon::now();

        $query
            ->where('active_from', '<', $now)
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
}
