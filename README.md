# Laravel Dismissibles

A Laravel package for easily handling recurring, dismissible objects like popups/notifications on the server side.

## What problem does this solve?
Say you have a (dismissible) popup you want to show every day for a week. Users can dismiss it and it should not show up again for the rest of the day until the next day.

This packages handles everything the complexer logic regarding whether it (dismissible) should be shown to the current user at the current moment. It's highly customizable, making it very flexible for many scenario's.

Because it is serverside we can easily get statistics like who dismissed what, when and where.

## Installation
1. Require the package in your Laravel application
```shell
composer require thijsschalk/laravel-dismissibles
```

2. Run the migrations to create the database tables
```shell
php artisan migrate
```

## How to use
Add the trait to any model which should be able to dismiss objects (like `App\Models\User`):
```php
use HasDismissibles;
```

Create a dismissible object:
```php
Dismissible::create([
    'name'          => 'Popup 1', // Prevent magic variables, create a config and do something like: config('dismissibles.popup_one.name');
    'active_from'   => Carbon::yesterday(),
    'active_until'  => Carbon::now()->addWeek(),
]);
```

Check whether it has been dismissed:
```php
$popup = Dismissible::firstWhere('name', 'Popup 1');
    
$showPopup = !$user->hasDismissed($popup);
```

Dismissing:
```php
$user->dismiss($dismissible)->forToday();
$user->dismiss($dismissible)->forHours($hours);
$user->dismiss($dismissible)->forDays($days);
$user->dismiss($dismissible)->forWeeks($weeks);
$user->dismiss($dismissible)->forMonths($months);
$user->dismiss($dismissible)->forYears($years);
$user->dismiss($dismissible)->forThisCalendarWeek();
$user->dismiss($dismissible)->forThisCalendarMonth();
$user->dismiss($dismissible)->forThisCalendarQuarter();
$user->dismiss($dismissible)->forThisCalendarYear();
$user->dismiss($dismissible)->forever();
$user->dismiss($dismissible)->until($dateTime);
```
