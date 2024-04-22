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

### 1. Add the trait to any model
```php
use ThijsSchalk\LaravelDismissibles\Traits\HasDismissibles;

class User
{
    use HasDismissibles;
    ...
}

```

### 2. Create dismissible/check has dismissed
Determining whether to show the dismissible do something like this in your controller:
```php

use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use Illuminate\Support\Facades\Date;

class SomeController {
    public function index()
    {
        ...
    
        // Only existing and active(!) Dismissibles are returned
        // It's recommended to fetch these attribute values through something like: config('dismissibles.new_years_popup.*) 
        $dismissible = Dismissible::firstOrCreate(
            ['name' => 'Happy New Year popup'], 
            [
                'active_from'  => Date::createFromFormat('d-m-Y', '01-01-2030'),
                'active_until' => Date::createFromFormat('d-m-Y', '06-01-2030'),
            ],
        );
        
        $showPopup = !$user->hasDismissed($popup);
        
        ...
    }
}
```

### 3. Dismissing
```php
class SomeController {
    public function dismiss()
    {
        ...
        
        // Any of these:
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
        
        ...
    }
}
```

Need extra data regarding the dismissal? All methods above allow you to pass an `$extraData` array as last parameter which will be written to the `dismissals` table as json.

## Database tables
The database structure allows you to easily track activity regarding dismissibles. Due to the `extra_data` column it's also very flexible!


### Dismissibles (popups, notifications, modals)
| id | uuid                                 | name                 | active_from         | active_until        | created_at          | updated_at          |
|----|--------------------------------------|----------------------|---------------------|---------------------|---------------------|---------------------|
| 3  | 0022d55a-03fa-4ff5-a0d0-670a6a8c9d8b | Happy New Year popup | 2030-01-01 00:00:00 | 2030-01-06 23:59:59 | 2029-12-15 17:35:54 | 2029-12-15 17:35:54 |


### Dismissals (activity)
| id | dismissible_id | dismisser_type  | dismisser_id | dismissed_until     | extra_data                   | created_at          | updated_at          |
|----|----------------|-----------------|--------------|---------------------|------------------------------|---------------------|---------------------|
| 15 | 3              | App\Models\User | 328          | 2030-01-02 23:59:59 | "{\"route\":\"home.index\"}" | 2030-01-02 17:35:54 | 2030-01-02 17:35:54 |

