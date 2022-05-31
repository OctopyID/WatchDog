<?php

namespace Octopy\WatchDog\Tests;

use Illuminate\Database\Eloquent\Collection;
use Octopy\WatchDog\Concerns\HasAbility;
use Octopy\WatchDog\Concerns\HasRole;

/**
 * This Model is used for testing only.
 * @property Collection $roles
 * @method static create(string[] $array)
 */
class User extends \Illuminate\Foundation\Auth\User
{
    use HasRole, HasAbility;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];
}
