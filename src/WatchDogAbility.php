<?php

namespace Octopy\WatchDog;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Checkers\AbilityChecker;
use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\WatchDogCache as Cache;

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
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    public function able(string $ability, Model|string $entity = null) : bool
    {
        return $this->checker->able($ability, $entity);
    }

    /**
     * @param  Arrayable|Role|array|string $abilities
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function assign(Arrayable|Role|array|string $abilities) : mixed
    {
        $abilities = $this->parse($abilities);

        foreach ($abilities as $role) {
            $this->model->abilities()->attach($role);
        }

        return $this->model;
    }

    /**
     * @param  Arrayable|Role|array|string $abilities
     * @return mixed
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function retract(Arrayable|Role|array|string $abilities) : mixed
    {
        $abilities = $this->parse($abilities);

        foreach ($abilities as $role) {
            $this->model->abilities()->detach($role);
        }

        return $this->model;
    }

    /**
     * @param  Arrayable|Ability|array|string $abilities
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function parse(Arrayable|Ability|array|string $abilities) : array
    {
        if ($abilities instanceof Ability) {
            $abilities = [$abilities];
        } else if (is_string($abilities)) {
            $abilities = explode('|', $abilities);
        } else if ($abilities instanceof Arrayable) {
            $abilities = $abilities->toArray();
        }

        return Cache::remember('abilities.' . md5(serialize($abilities)), function () use ($abilities) {
            return $this->findAbilities($abilities);
        });
    }

    /**
     * @param  array $abilities
     * @return array
     * @noinspection PhpUndefinedMethodInspection
     */
    protected function findAbilities(array $abilities) : array
    {
        foreach ($abilities as $index => $ability) {
            if (is_int($ability)) {
                $abilities[$index] = Ability::findOrFail($ability);
            } else if (is_string($ability)) {
                $abilities[$index] = Ability::where('name', $ability)->firstOrFail();
            }
        }

        return $abilities;
    }
}
