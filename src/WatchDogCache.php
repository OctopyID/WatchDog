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
            return Cache::flush();
        }

        return Cache::tags('watchdog')->flush();
    }

    /**
     * @param  string   $key
     * @param  int      $minutes
     * @param  callable $callback
     * @return mixed
     */
    public static function remember(string $key, int $minutes, callable $callback) : mixed
    {
        if (in_array(config('cache.default'), ['file', 'database', 'dynamodb'])) {
            return Cache::remember($key, $minutes, $callback);
        }

        return Cache::tags('watchdog')->remember($key, $minutes, $callback);
    }
}
