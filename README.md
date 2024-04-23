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

### 2. Create a dismissible (migration)
You can also do it inline using `firstOrCreate` instead of `firstWhere`.
```php
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
...

return new class () extends Migration {
    public function up(): void
    {
        DB::table('dismissibles')->insert([
            'name'         => 'Happy New Year popup',
            'active_from'  => Date::createFromFormat('d-m-Y', '01-01-2030'),
            'active_until' => Date::createFromFormat('d-m-Y', '06-01-2030'),
        ]);
    }
    
    ...
};
```

and run the migration
```php
php artisan migrate
```


### 3. Check whether it's dismissed
```php

use ThijsSchalk\LaravelDismissibles\Models\Dismissible;
use Illuminate\Support\Facades\Date;

class SomeController {
    public function index()
    {
        ...
    
        // Only existing and active(!) Dismissibles are returned
        // It's recommended to fetch the name through something like: config('dismissibles.new_years_popup.name') 
        $newYearsPopup = Dismissible::firstWhere(['name' => 'Happy New Year popup']);
        
        $showPopup = !$user->hasDismissed($newYearsPopup);
        
        ...
    }
}
```

### 4. Dismiss it
```php
class SomeController {
    public function dismiss()
    {
        ...
        
        $newYearsPopup = Dismissible::firstWhere(['name' => 'Happy New Year popup']);
        
        // Any of these:
        $user->dismiss($newYearsPopup)->forToday();
        $user->dismiss($newYearsPopup)->forHours($hours);
        $user->dismiss($newYearsPopup)->forDays($days);
        $user->dismiss($newYearsPopup)->forWeeks($weeks);
        $user->dismiss($newYearsPopup)->forMonths($months);
        $user->dismiss($newYearsPopup)->forYears($years);
        $user->dismiss($newYearsPopup)->forThisCalendarWeek();
        $user->dismiss($newYearsPopup)->forThisCalendarMonth();
        $user->dismiss($newYearsPopup)->forThisCalendarQuarter();
        $user->dismiss($newYearsPopup)->forThisCalendarYear();
        $user->dismiss($newYearsPopup)->forever();
        $user->dismiss($newYearsPopup)->until($dateTime);
        
        ...
    }
}
```

Need extra data regarding the dismissal? All methods above allow you to pass an `$extraData` array as last parameter which will be written to the `dismissals` table as json.

## Database tables
The database structure allows you to easily track activity regarding dismissibles. Due to the `extra_data` column it's also very flexible!


### Dismissibles (popups, notifications, modals)
| id | name                 | active_from         | active_until        | created_at          | updated_at          |
|----|----------------------|---------------------|---------------------|---------------------|---------------------|
| 3  | Happy New Year popup | 2030-01-01 00:00:00 | 2030-01-06 23:59:59 | 2029-12-15 17:35:54 | 2029-12-15 17:35:54 |


### Dismissals (activity)
| id | dismissible_id | dismisser_type  | dismisser_id | dismissed_until     | extra_data                   | created_at          | updated_at          |
|----|----------------|-----------------|--------------|---------------------|------------------------------|---------------------|---------------------|
| 15 | 3              | App\Models\User | 328          | 2030-01-02 23:59:59 | "{\"route\":\"home.index\"}" | 2030-01-02 17:35:54 | 2030-01-02 17:35:54 |

