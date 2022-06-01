<?php

namespace Octopy\WatchDog;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Checkers\RoleChecker;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\WatchDogCache as Cache;

class WatchDogRole
{
    /**
     * @var RoleChecker
     */
    protected RoleChecker $checker;

    /**
     * @param  Model $model
     */
    public function __construct(protected Model $model)
    {
        $this->checker = new RoleChecker($model);
    }

    /**
     * @param  Role|array|string $roles
     * @return bool
     */
    public function has(Role|array|string $roles) : bool
    {
        return $this->checker->has($roles);
    }

    /**
     * @param  Arrayable|Role|array|string|int $roles
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function assign(Arrayable|Role|array|string|int $roles) : mixed
    {
        $roles = $this->parse($roles);

        foreach ($roles as $role) {
            $this->model->roles()->attach($role);
        }

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string|int $roles
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function retract(Arrayable|Role|array|string|int $roles) : mixed
    {
        $roles = $this->parse($roles);

        foreach ($roles as $role) {
            $this->model->roles()->detach($role);
        }

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string|int $roles
     * @return array
     */
    protected function parse(Arrayable|Role|array|string|int $roles) : array
    {
        if ($roles instanceof Role || is_int($roles)) {
            $roles = [$roles];
        } else if (is_string($roles)) {
            $roles = explode('|', $roles);
        } else if ($roles instanceof Arrayable) {
            $roles = $roles->toArray();
        }

        if (config('watchdog.cache.enabled')) {
            return Cache::remember('roles.' . md5(serialize($roles)), function () use ($roles) {
                return $this->findRoles($roles);
            });
        }

        return $this->findRoles($roles);
    }

    /**
     * @param  array $roles
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function findRoles(array $roles) : array
    {
        foreach ($roles as $index => $role) {
            if (is_int($role)) {
                $roles[$index] = Role::findOrFail($role);
            } else if (is_string($role)) {
                $roles[$index] = Role::where('name', $role)->firstOrFail();
            }
        }

        return $roles;
    }
}
