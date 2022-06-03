<?php

namespace Octopy\WatchDog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Permission extends Pivot
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'title', 'description', 'forbidden',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'forbidden' => 'boolean',
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
