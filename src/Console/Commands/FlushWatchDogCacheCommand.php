<?php

namespace Octopy\WatchDog\Console\Commands;

use Illuminate\Console\Command;
use Octopy\WatchDog\WatchDogCache;

class FlushWatchDogCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'watchdog:flush';

    /**
     * @var string
     */
    protected $description = 'Flush WatchDog cache';

    /**
     * @return void
     */
    public function handle() : void
    {
        if (WatchDogCache::purge()) {
            $this->info('WatchDog cache flushed.');
        }
    }
}
