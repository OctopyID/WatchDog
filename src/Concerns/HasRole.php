<?php

namespace Octopy\WatchDog\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Octopy\WatchDog\WatchDogRole;

trait HasRole
{
    /**
     * @return WatchDogRole
     */
    public function getRoleAttribute() : WatchDogRole
    {
        return new WatchDogRole($this);
    }

    /**
     * @return MorphToMany
     */
    public function roles() : MorphToMany
    {
        return $this->morphToMany(config('watchdog.models.role'), 'entity', config('watchdog.tables.assigned_roles'), 'entity_id');
    }
}
