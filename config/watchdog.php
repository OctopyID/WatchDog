<?php

use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\Role;

return [
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Manage WatchDog's cache configurations. It uses the driver defined in the
    | config/cache.php file.
    |
    */
    'cache'  => [
        /*
        |--------------------------------------------------------------------------
        | Use cache in the package
        |--------------------------------------------------------------------------
        |
        | Defines if WatchDog will use Laravel's Cache to cache the roles and permissions.
        | NOTE: Currently the database check does not use cache.
        |
        */
        'enabled'    => env('WATCHDOG_ENABLE_CACHE', env('APP_ENV') === 'production'),

        /*
        |--------------------------------------------------------------------------
        | Time to store in cache WatchDog's roles and permissions.
        |--------------------------------------------------------------------------
        |
        | Determines the time in SECONDS to store WatchDog's roles and permissions in the cache.
        |
        */
        'expiration' => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | WatchDog Models
    |--------------------------------------------------------------------------
    |
    | These are the models used by WatchDog to define the roles, permissions and teams.
    | If you want the WatchDog models to be in a different namespace or
    | to have a different name, you can do it here.
    |
    */
    'models' => [

        'role' => Role::class,

        'ability' => Ability::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | WatchDog Tables
    |--------------------------------------------------------------------------
    |
    | These are the tables used by WatchDog to store all the authorization data.
    |
    */
    'tables' => [

        'roles' => 'roles',

        'abilities' => 'abilities',

        'permissions' => 'permissions',

        'assigned_roles' => 'assigned_roles',
    ],
];
