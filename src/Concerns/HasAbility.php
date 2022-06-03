<?php

namespace Octopy\WatchDog\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Octopy\WatchDog\WatchDogAbility;

/**
 * @property WatchDogAbility $ability
 */
trait HasAbility
{
    /**
     * @return WatchDogAbility
     */
    public function getAbilityAttribute() : WatchDogAbility
    {
        return new WatchDogAbility($this);
    }

    /**
     * @return MorphToMany
     */
    public function abilities() : MorphToMany
    {
        return $this->morphToMany(config('watchdog.models.ability'), 'entity', config('watchdog.tables.permissions'), 'entity_id')->withPivot('forbidden');
    }
}
