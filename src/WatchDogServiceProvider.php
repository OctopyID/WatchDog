<?php

namespace Octopy\WatchDog;

use Illuminate\Support\ServiceProvider;
use Octopy\WatchDog\Console\Commands\FlushWatchDogCacheCommand;
use Octopy\WatchDog\Http\Middleware\WatchDogAbilityMiddleware;
use Octopy\WatchDog\Http\Middleware\WatchDogRoleMiddleware;

class WatchDogServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/watchdog.php', 'watchdog'
        );

        if ($this->app->runningInConsole()) {
            $this->publish();

            $this->commands([
                FlushWatchDogCacheCommand::class,
            ]);
        }

        $this->app['router']->aliasMiddleware('role', WatchDogRoleMiddleware::class);
        $this->app['router']->aliasMiddleware('ability', WatchDogAbilityMiddleware::class);
    }

    /**
     * @return void
     */
    private function publish() : void
    {
        $date = now()->format('Y_m_d_His');

        $this->publishes([
            __DIR__ . '/../config/watchdog.php'                           => config_path('watchdog.php'),
            __DIR__ . '/../database/migrations/create_watchdog_table.php' => database_path('migrations/' . $date . '_create_watchdog_table.php'),
        ], 'watchdog');
    }
}
