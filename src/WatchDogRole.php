<?php

namespace Octopy\WatchDog;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Checkers\RoleChecker;
use Octopy\WatchDog\Models\Role;

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
     * @param  Arrayable|Role|array|string $roles
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function assign(Arrayable|Role|array|string $roles) : mixed
    {
        $roles = $this->parse($roles);

        foreach ($roles as $role) {
            $this->model->roles()->attach($role);
        }

        WatchDogCache::instance()->flush();

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string $roles
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function retract(Arrayable|Role|array|string $roles) : mixed
    {
        $roles = $this->parse($roles);

        foreach ($roles as $role) {
            $this->model->roles()->detach($role);
        }

        WatchDogCache::instance()->flush();

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string $roles
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function parse(Arrayable|Role|array|string $roles) : array
    {
        if ($roles instanceof Role) {
            $roles = [$roles];
        } else if (is_string($roles)) {
            $roles = explode('|', $roles);
        } else if ($roles instanceof Arrayable) {
            $roles = $roles->toArray();
        }

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
