<?php

declare(strict_types=1);

namespace ThijsSchalk\LaravelDismissibles\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
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
        'active_from'  => 'datetime',
        'active_until' => 'datetime',
    ];

    protected static function newFactory(): Factory
    {
        return DismissibleFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function dismissals(): HasMany
    {
        return $this->hasMany(Dismissal::class);
    }
}
