<?php

namespace Octopy\WatchDog;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

class WatchDogCache
{
    /**
     * @return Cache|TaggedCache
     */
    public static function instance() : Cache|TaggedCache
    {
        if (! in_array(config('cache.default'), ['file', 'database', 'dynamodb'])) {
            return Cache::tags('watchdog');
        }

        return new Cache;
    }
}
