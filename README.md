# Laravel Dismissible

A Laravel package for easily handling recurring, dismissible objects like popups/notifications.

## What problem does this solve?
Say you have a (dismissible) popup you want to show every day for a week. Users can dismiss it and it should not show up again for the rest of the day until the next day.

This packages handles everything the complexer logic regarding whether it (dismissible) should be shown to the current user at the current moment. It's highly customizable, making it very flexible for many scenario's.

## Installation
1. Require the package in your Laravel application
```shell
composer require thijsschalk/laravel-dismissible
```
 
2. Publish the database migrations
```shell
php artisan vendor:publish --provider="ThijsSchalk\LaravelDismissible\LaravelDismissibleServiceProvider"
```

3. Run the migrations to create the database tables
```shell
php artisan migrate
```

## How to use
These examples are with Inertia, but you can basically use it with any frontend.

Add the trait to any model (usually `User`) which should be able to dismiss objects:
```php
use CanDismiss;
```

Create a dismissible object:
```php
Dismissible::create([
    ...TODO
]);
```

Now you can do stuff like:
```php
public function index(): Response
{
    $popup = Dismissible::firstWhere('name', 'my-popup');

    return Inertia::render('home/Index', [
        'popupIsVisible' => $user->shouldSeeDismissible($popup),
    ]);
}

public function dismiss(Request $request, Dismissible $dismissible): RedirectResponse
{
    $user = $request->user();
    
    $user->dismiss($dismissible);
    
    return redirect(route('home.index'));
}

```

