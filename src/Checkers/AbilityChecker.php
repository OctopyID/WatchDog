<?php

namespace Octopy\WatchDog\Checkers;

use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Concerns\HasRole;
use Octopy\WatchDog\WatchDogCache as Cache;
use ReflectionClass;

class AbilityChecker
{
    /**
     * @param  Model $entity
     */
    public function __construct(protected Model $entity)
    {
        //
    }

    /**
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    public function able(string $ability, Model|string $entity = null) : bool
    {
        if (config('watchdog.cache.enabled')) {
            return Cache::remember(md5($ability . serialize($entity)), function () use ($entity, $ability) {
                return $this->checkEntityAbility($ability, $entity);
            });
        }

        return $this->checkEntityAbility($ability, $entity);
    }

    /**
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    private function checkEntityAbility(string $ability, Model|string|null $entity) : bool
    {
        $result = $this->checkEntityHasAbility($ability, $entity);

        // If the entity has no direct ability, check if the entity has a role that has the ability.
        if (! $result && $this->checkEntityHasRole()) {
            return $this->checkEntityHasAbilityByRole($ability, $entity);
        }

        return $result;
    }

    /**
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    private function checkEntityHasAbilityByRole(string $ability, Model|string|null $entity) : bool
    {
        // TODO : implement query builder instead of looping through roles.
        foreach ($this->entity->roles as $role) {
            if ($role->ability->able($ability, $entity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string            $ability
     * @param  Model|string|null $entity
     * @return bool
     */
    private function checkEntityHasAbility(string $ability, Model|string|null $entity) : bool
    {
        $query = $this->entity->abilities()->where('name', $ability);

        if ($entity) {
            // if the given model is an object, then we will check the capabilities by including the record id that the role can handle.
            if ($entity instanceof Model) {
                return $query
                    ->where(config('watchdog.tables.abilities') . '.entity_type', get_class($entity))
                    ->where(config('watchdog.tables.abilities') . '.entity_id', $entity->{$entity->getKeyName()})
                    ->exists();
            }

            // if the given model is a string, then we need to check the availability of capabilities.
            $query->where(config('watchdog.tables.abilities') . '.entity_type', $entity);

            if ($query->exists()) {
                return is_null($query->first()->entity_id); // Then we check whether the capability is for the whole record or not .
            }
        }

        return $query->exists();
    }

    /**
     * @return bool
     */
    private function checkEntityHasRole() : bool
    {
        return in_array(HasRole::class, (new ReflectionClass($this->entity))->getTraitNames()) && $this->entity->roles->isNotEmpty();
    }
}
