<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogRoleMiddlewareTest extends TestCase
{
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
    public function testEntityWithValidRoleNotInExceptionCanAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        Route::middleware('role.except:user')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertSee('Hello World');
    }

    /**
     * @return void
     */
    public function testEntityWithValidRoleInExceptionCanNotAccessRoute() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $user->role->assign($role);

        Route::middleware('role.except:admin')->get('/', function () {
            return 'Hello World';
        });

        $this->actingAs($user)->get('/')->assertStatus(403);
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
}
