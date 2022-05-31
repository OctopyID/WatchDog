<?php

namespace Octopy\WatchDog;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Checkers\AbilityChecker;
use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\Role;

class WatchDogAbility
{
    /**
     * @var AbilityChecker
     */
    protected AbilityChecker $checker;

    /**
     * @param  Model $model
     */
    public function __construct(protected Model $model)
    {
        $this->checker = new AbilityChecker($model);
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
            $this->model->abilities()->attach($role);
        }

        WatchDogCache::purge();

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
            $this->model->abilities()->detach($role);
        }

        WatchDogCache::purge();

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string $roles
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function parse(Arrayable|Role|array|string $roles) : array
    {
        if ($roles instanceof Ability) {
            $roles = [$roles];
        } else if (is_string($roles)) {
            $roles = explode('|', $roles);
        } else if ($roles instanceof Arrayable) {
            $roles = $roles->toArray();
        }

        foreach ($roles as $index => $role) {
            if (is_int($role)) {
                $roles[$index] = Ability::findOrFail($role);
            } else if (is_string($role)) {
                $roles[$index] = Ability::where('name', $role)->firstOrFail();
            }
        }

        return $roles;
    }

    /**
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    public function able(string $ability, Model|string $entity = null) : bool
    {
        return $this->checker->able($ability, $entity);
    }
}
