<p align="center">
    <img src="watchdog.gif" alt="WatchDog">
</p>

<p align="center">
    <img src="https://img.shields.io/github/workflow/status/OctopyID/WatchDog/Run%20Unit%20Testing?style=for-the-badge&label=tests" alt="Tests">
    <img src="https://img.shields.io/packagist/v/octopyid/watchdog.svg?style=for-the-badge" alt="Version">
    <img src="https://img.shields.io/packagist/dt/octopyid/watchdog.svg?style=for-the-badge&color=F28D1A" alt="Downloads">
    <img src="https://img.shields.io/github/license/OctopyID/WatchDog?style=for-the-badge&color=5D9BB6" alt="License">
</p>

# Laravel Watch Dog

Watch Dog is a package for role management and the ability to control your Laravel applications.

## Features

- Roles, permissions and abilities.
- The ability of the role of the model or record.
- The ability of the entity to the model or record.

It also includes middleware and configurable cache.

## Installation

To install the package, simply follow the steps below.

Install the package using Composer:

1. Install WatchDog using Composer.

```bash
composer require octopyid/watchdog:dev-main
```

2. Publish the package.

```bash
php artisan vendor:publish --provider="Octopy\WatchDog\WatchDogServiceProvider"
```

3. Add WatchDog Traits to your model.

```php
<?php

use Octopy\WatchDog\Concerns\HasAbility;
use Octopy\WatchDog\Concerns\HasRole;

class User extends Authenticatable
{
    use HasRole, HasAbility;
}
```

4. Finally, run the migrations:

```bash
php artisan migrate
```

## Usage

### Assign Role to User

```php
$role = Role::create([
    'name' => 'foo',
]);

# Assign
$user->role->assign('foo');
$user->role->assign($role);

# Check
$user->role->has('foo'); 
$user->role->has($role);

# Remove
$user->role->retract('foo');
$user->role->retract($role);
```

### Assign Ability to Role

#### Ability Without Model

```php
$ability = Ability::create([
    'name' => 'delete',
]);

# Assign
$role->ability->assign('delete');
$role->ability->assign($ability);

# Check By User
$user->ability->able('delete');

# Check By Role
$role->ability->able('delete');

# Remove
$user->ability->retract('foo');
$user->ability->retract($ability);
```

#### Ability With Model

```php
# You want to give abilities only to certain records of a model.
$ability = Ability::create([
    'name' => 'delete',
    'entity_id' => 1,
    'entity_type' => \App\Models\Post::class,
]);

# Or do you want to give abilities to all records of a model.
$ability = Ability::create([
    'name' => 'delete',
    'entity_id' => null, // null means all records
    'entity_type' => \App\Models\Post::class,
]);

# Check By User
$user->ability->able('delete', $post);
$user->ability->able('delete', Post::class);
```

### Assign Ability to Entity

#### Ability Without Model

```php
$ability = Ability::create([
    'name' => 'delete',
]);

# Assign
$user->ability->assign('delete');
$user->ability->assign($ability);

# Check
$user->ability->able('delete');

# Remove
$user->ability->retract('foo');
$user->ability->retract($ability);
```

#### Ability With Model

```php
# You want to give abilities only to certain records of a model.
$ability = Ability::create([
    'name' => 'delete',
    'entity_id' => 1,
    'entity_type' => \App\Models\Post::class,
]);

# Or do you want to give abilities to all records of a model.
$ability = Ability::create([
    'name' => 'delete',
    'entity_id' => null, // null means all records
    'entity_type' => \App\Models\Post::class,
]);

# To check
$user->ability->able('delete', $post);
$user->ability->able('delete', Post::class);
```

## Disclaimer

All maintainers, contributors, and the package itself are not responsible for any damages, direct or indirect, that may occur as a result of using this package.

## Security

If you discover any security related issues, please email [supianidz@octopy.id](mailto:supianidz@octopy.id) instead of using the issue tracker.

## License

This package is licensed under the MIT license.
