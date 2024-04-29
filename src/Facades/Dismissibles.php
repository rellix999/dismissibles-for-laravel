<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Facades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;
use Rellix\Dismissibles\Concerns\Dismiss;
use Rellix\Dismissibles\Contracts\Dismisser;
use Rellix\Dismissibles\Models\Dismissible;

class Dismissibles extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dismissibles';
    }

    /**
     * Gets the active(!) dismissible from the database by name.
     */
    public static function get(string $name): ?Dismissible
    {
        return Dismissible::active()->firstWhere('name', $name);
    }

    /**
     * Returns whether the dismissible should be shown to the dismisser at the current moment.
     */
    public static function shouldShow(string $name, Dismisser $dismisser): bool
    {
        $dismissible = self::get($name);
        if (!$dismissible) {
            return false;
        }

        return !self::isDismissed($name, $dismisser);
    }

    /**
     * Returns a Dismiss object which allows you to dismiss the dismissible for a specified period.
     */
    public static function dismiss(string $name, Dismisser $dismisser): ?Dismiss
    {
        $dismissible = self::get($name);
        if (!$dismissible) {
            return null;
        }

        return new Dismiss($dismisser, $dismissible);
    }

    /**
     * Returns whether a dismissible is currently dismissed by the dismisser.
     */
    public static function isDismissed(string $name, Dismisser $dismisser): bool
    {
        $dismissible = Dismissible::firstWhere('name', $name);

        return $dismisser->dismissals()
            ->where('dismissible_id', $dismissible->id)
            ->where(function (Builder $query) {
                $query
                    ->where('dismissed_until', '>', Carbon::now())
                    ->orWhereNull('dismissed_until');
            })
            ->exists();
    }
}
