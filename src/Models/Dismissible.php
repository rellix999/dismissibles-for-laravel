<?php

namespace ThijsSchalk\LaravelDismissible\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Dismissible extends Model
{
    protected $fillable = [
        'name',
        'active_from',
        'active_until',
    ];

    protected $casts = [
        'active_from'  => 'datetime',
        'active_until' => 'datetime',
    ];

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
