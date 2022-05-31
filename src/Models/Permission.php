<?php

namespace Octopy\WatchDog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Permission extends Pivot
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'title', 'description',
    ];

    /**
     * @param  array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config(
            'watchdog.tables.permissions', 'permissions'
        ));
    }
}
