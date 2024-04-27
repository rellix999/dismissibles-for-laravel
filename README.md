# Laravel Dismissibles

A Laravel package for easily handling recurring, dismissible objects like popups/notifications/modals on the server side.

## What problem does this solve?
Say you have a dismissible popup you want to show to every user, daily for a week. Users can dismiss it and it should not show up again for the rest of the day until the next day.

This packages handles the complex logic regarding whether it (dismissible) should be shown to the current user at the current moment. It's highly customizable, making it very flexible for many scenario's.

Because it's serverside we can easily get statistics like who dismissed what, when and where.

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

### 1. Add the interface and trait to any model
```php
use ThijsSchalk\LaravelDismissibles\Contracts\Dismisser;
use ThijsSchalk\LaravelDismissibles\Traits\HasDismissibles;

class User implements Dismisser
{
    use HasDismissibles;
    ...
}

```

### 2. Create a dismissible (migration)
You can also create an artisan command or do it inline using `firstOrCreate` instead of `firstWhere`.
```php
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
...

return new class () extends Migration {
    public function up(): void
    {
        DB::table('dismissibles')->insert([
            'name'         => 'Happy New Year popup', // This is your **unique** identifier
            'active_from'  => Date::createFromFormat('d-m-Y', '01-01-2030'),
            'active_until' => Date::createFromFormat('d-m-Y', '06-01-2030'), // If there is no end date, set it to `null`
            'created_at'   => Date::now(),
            'updated_at'   => Date::now(),
        ]);
    }
    
    ...
};
```

and run the create migration:
```php
php artisan migrate
```


### 3. Check whether it should be shown at the current moment
```php
use ThijsSchalk\LaravelDismissibles\Facades\Dismissibles;

class SomeController {
    public function index()
    {
        ...
    
        // It's recommended to fetch the name through something like: config('dismissibles.new_years_popup.name')
        
        $showPopup = Dismissibles::shouldShow('Happy New Year popup', $user);
        
        ...
    }
}
```

### 4. Dismiss it for a specified period
```php
use ThijsSchalk\LaravelDismissibles\Facades\Dismissibles;

class SomeController {
    public function dismiss()
    {
        ...
        
        // Dismiss for a specified period using any of these:
        
        Dismissibles::dismiss('Happy New Year popup', $user)
            ->forToday();
            ->forHours($hours);
            ->forDays($days);
            ->forWeeks($weeks);
            ->forMonths($months);
            ->forYears($years);
            ->forThisCalendarWeek();
            ->forThisCalendarMonth();
            ->forThisCalendarQuarter();
            ->forThisCalendarYear();
            ->forever();
            ->until($dateTime);
    }
}
```

### Notes
- Need extra data regarding the dismissal? All methods above allow you to pass an `$extraData` array as last parameter which will be written to the `dismissals` table as json.
- You can use the `Dismissible` and `Dismissal` Eloquent models as usual.
- I've provided a couple more facade methods for your convenience. Feel free to request more. 
```php
public static function get(string $name): ?Dismissible;
public static function isDismissed(string $name, Dismisser $dismisser): bool;
```

## Database tables
The database structure allows you to easily track activity regarding dismissibles. Due to the `extra_data` column it's also very flexible!

### dismissibles (popups, notifications, modals)
| id | name                 | active_from         | active_until        | created_at          | updated_at          |
|----|----------------------|---------------------|---------------------|---------------------|---------------------|
| 3  | Happy New Year popup | 2030-01-01 00:00:00 | 2030-01-06 23:59:59 | 2029-12-15 17:35:54 | 2029-12-15 17:35:54 |


### dismissals (activity)
| id | dismissible_id | dismisser_type  | dismisser_id | dismissed_until     | extra_data                   | created_at          | updated_at          |
|----|----------------|-----------------|--------------|---------------------|------------------------------|---------------------|---------------------|
| 15 | 3              | App\Models\User | 328          | 2030-01-02 23:59:59 | "{\"route\":\"home.index\"}" | 2030-01-02 17:35:54 | 2030-01-02 17:35:54 |

## Buy me a coffee
If you like this package, consider [buying me a coffee](https://www.paypal.com/donate/?business=E6QBKXWLXMD92&no_recurring=1&item_name=Buy+me+a+coffee&currency_code=EUR&amount=2.50) :-).
