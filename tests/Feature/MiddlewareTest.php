<?php

namespace Octopy\WatchDog\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testEntityWithValidRoleCanAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        Route::middleware('role:admin')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertSee('Hello World');

        $user->role->retract($role);

        $this->actingAs($user)->get('/')->assertDontSee('Hello World');
    }

    /**
     * @return void
     */
    public function testEntityWithInvalidRoleCannotAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'user',
        ]);

        $user->role->assign($role);

        Route::middleware('role:admin')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertStatus(403);
    }

    /**
     * @return void
     */
    public function testEntityWithValidRoleAndAbilityCanAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        $ability = $user->abilities()->create([
            'name' => 'bar',
        ]);

        $role->abilities()->attach($ability);

        Route::middleware('ability:bar')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertSee('Hello World');
    }

    /**
     * @return void
     */
    public function testEntityWithInvalidRoleAndAbilityCannotAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        $ability = $user->abilities()->create([
            'name' => 'bar',
        ]);

        $role->abilities()->attach($ability);

        Route::middleware('ability:foo')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertStatus(403);
    }
}
