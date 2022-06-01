<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogAbilityMiddlewareTest extends TestCase
{
    use RefreshDatabase;

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
