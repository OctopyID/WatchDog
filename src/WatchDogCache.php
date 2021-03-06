<?php

namespace Octopy\WatchDog;

use Illuminate\Support\Facades\Cache;

class WatchDogCache
{
    /**
     * @return bool
     */
    public static function purge() : bool
    {
        if (in_array(config('cache.default'), ['file', 'database', 'dynamodb'])) {
            // @codeCoverageIgnoreStart
            return Cache::flush();
            // @codeCoverageIgnoreEnd
        }

        return Cache::tags('watchdog')->flush();
    }

    /**
     * @param  string   $key
     * @param  callable $callback
     * @return mixed
     */
    public static function remember(string $key, callable $callback) : mixed
    {
        if (in_array(config('cache.default'), ['file', 'database', 'dynamodb'])) {
            // @codeCoverageIgnoreStart
            return Cache::remember('watchdog.' . $key, config('watchdog.cache.expiration'), $callback);
            // @codeCoverageIgnoreEnd
        }

        return Cache::tags('watchdog')->remember('watchdog.' . $key, config('watchdog.cache.expiration'), $callback);
    }
}
