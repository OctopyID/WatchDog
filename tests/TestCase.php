<?php

namespace Octopy\WatchDog\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Octopy\WatchDog\WatchDogServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    protected function defineDatabaseMigrations() : void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * @param  Application $app
     * @return string[]
     */
    protected function getPackageProviders($app) : array
    {
        return [
            WatchDogServiceProvider::class,
        ];
    }
}
