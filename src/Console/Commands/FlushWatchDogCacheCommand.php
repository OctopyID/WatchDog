<?php

namespace Octopy\WatchDog\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        if (Cache::tags('watchdog')->flush()) {
            $this->info('WatchDog cache flushed.');
        }
    }
}
