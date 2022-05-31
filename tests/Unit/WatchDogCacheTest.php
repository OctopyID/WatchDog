<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Unit;

use Octopy\PHPUnitExtra\AssertQueryCount;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogCacheTest extends TestCase
{
    use AssertQueryCount;

    /**
     * @return void
     */
    public function testEntityCanCacheTheirRoles() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        config([
            'watchdog.cache.enabled' => true,
        ]);

        $this->assertQueryCountMatches(1, function () use ($user) {
            $this->assertTrue($user->role->has('admin'));
        });

        // Assert that the cache is hit.
        $this->assertQueryCountMatches(0, function () use ($user) {
            $this->assertTrue($user->role->has('admin'));
        });
    }

    /**
     * @return void
     */
    public function testEntityCanCacheTheirAbilities() : void
    {
        $user = User::create([
            'name' => 'foo',
        ]);

        $ability = $user->abilities()->create([
            'name' => 'bar',
        ]);

        $user->ability->assign($ability);

        config([
            'watchdog.cache.enabled' => true,
        ]);

        $this->assertQueryCountMatches(1, function () use ($user) {
            $this->assertTrue($user->ability->able('bar'));
        });

        // Assert that the cache is hit.
        $this->assertQueryCountMatches(0, function () use ($user) {
            $this->assertTrue($user->ability->able('bar'));
        });
    }

    /**
     * @return void
     */
    public function testRoleCanCacheTheirAbilities() : void
    {
        $role = Role::create([
            'name' => 'foo',
        ]);

        $ability = $role->abilities()->create([
            'name' => 'bar',
        ]);

        $role->ability->assign($ability);

        config([
            'watchdog.cache.enabled' => true,
        ]);

        $this->assertQueryCountMatches(1, function () use ($role) {
            $this->assertTrue($role->ability->able('bar'));
        });

        // Assert that the cache is hit.
        $this->assertQueryCountMatches(0, function () use ($role) {
            $this->assertTrue($role->ability->able('bar'));
        });
    }

    /**
     * @return void
     */
    public function testItCanFlushCacheByCommand() : void
    {
        $role = Role::create([
            'name' => 'foo',
        ]);

        $ability = $role->abilities()->create([
            'name' => 'bar',
        ]);

        $role->ability->assign($ability);

        config([
            'watchdog.cache.enabled' => true,
        ]);

        $this->assertQueryCountMatches(1, function () use ($role) {
            $this->assertTrue($role->ability->able('bar'));
        });

        // Assert that the cache is hit.
        $this->assertQueryCountMatches(0, function () use ($role) {
            $this->assertTrue($role->ability->able('bar'));
        });

        $this->artisan('watchdog:flush');

        $this->assertQueryCountMatches(1, function () use ($role) {
            $this->assertTrue($role->ability->able('bar'));
        });
    }
}
