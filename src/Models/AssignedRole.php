<?php

namespace Octopy\WatchDog\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedRole extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'role_id', 'entity_id', 'entity_type',
    ];

    /**
     * @param  array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config(
            'watchdog.tables.assigned_roles', 'assigned_roles'
        ));
    }
}
