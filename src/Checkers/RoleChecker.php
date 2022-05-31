<?php

namespace Octopy\WatchDog\Checkers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Octopy\WatchDog\Models\Role;

class RoleChecker
{
    /**
     * @param  Model $entity
     */
    public function __construct(protected Model $entity)
    {
        //
    }

    /**
     * @param  string|string[]|Role|Role[] $roles
     * @return bool
     */
    public function has(Role|array|string $roles) : bool
    {
        if ($roles instanceof Arrayable) {
            $roles = $roles->toArray();
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (config('watchdog.cache.enabled')) {
            return Cache::tags('watchdog')
                ->remember(md5(serialize($roles)), config('watchdog.cache.expiration'), function () use ($roles) {
                    return $this->checkEntityHasRole($roles);
                });
        }

        return $this->checkEntityHasRole($roles);
    }

    /**
     * @param  mixed $roles
     * @return bool
     */
    public function checkEntityHasRole(mixed $roles) : bool
    {
        return $this->entity->roles()->select('name')->whereIn('name', $roles)->exists();
    }
}
