<?php

namespace Octopy\WatchDog\Models;

use Illuminate\Database\Eloquent\Model;
use Octopy\WatchDog\Concerns\HasAbility;

class Role extends Model
{
    use HasAbility;

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
            'watchdog.tables.roles', 'roles'
        ));
    }
}
