<?php

declare(strict_types=1);

namespace Rellix\Dismissibles\Facades;

use Illuminate\Database\Eloquent\Collection;
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
     * Returns the active dismissible if it exists.
     */
    public static function get(string $name): ?Dismissible
    {
        return Dismissible::active()->firstWhere('name', $name);
    }

    /**
     * Returns all active dismissibles that should be visible to the $dismisser.
     *
     * @return Collection<int, Dismissible>
     */
    public static function getAllFor(Dismisser $dismisser): Collection
    {
        return Dismissible::query()
            ->active()
            ->notDismissedBy($dismisser)
            ->orderBy('active_from', 'asc')
            ->get();
    }

    /**
     * Returns whether the dismissible should be visible to the dismisser at the current moment.
     */
    public static function shouldBeVisible(string $name, Dismisser $dismisser): bool
    {
        $dismissible = self::get($name);

        return $dismissible && !$dismissible->isDismissedBy($dismisser);
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
        /** @var Dismissible $dismissible */
        $dismissible = Dismissible::firstWhere('name', $name);

        return $dismissible->isDismissedBy($dismisser);
    }
}
