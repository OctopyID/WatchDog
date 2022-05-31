<?php

namespace Octopy\WatchDog\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'title', 'entity_id', 'entity_type',
    ];

    /**
     * @param  array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config(
            'watchdog.tables.abilities', 'abilities'
        ));
    }
}
