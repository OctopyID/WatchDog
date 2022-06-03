<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Unit;

use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogAbilityTest extends TestCase
{
    /**
     * @return void
     */
    public function testEntityWithWildcardAbility() : void
    {
        $user = User::create([
            'name' => 'John Doe',
        ]);

        $victim = User::create([
            'name' => 'Jane Doe',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $role->ability->assign(Ability::create([
            'name'        => 'delete',
            'entity_id'   => null,
            'entity_type' => User::class,
        ]));

        $user->role->assign($role);

        $this->assertTrue($user->ability->able('delete', $victim));
        $this->assertTrue($user->ability->able('delete', User::class));
    }

    /**
     * @return void
     */
    public function testEntityWithNonWildcardAbility() : void
    {
        $user = User::create([
            'name' => 'John Doe',
        ]);

        $foo = User::create([
            'name' => 'Jane Doe',
        ]);

        $bar = User::create([
            'name' => 'Jane Doe',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $role->ability->assign(Ability::create([
            'name'        => 'delete',
            'entity_id'   => $foo->id,
            'entity_type' => User::class,
        ]));

        $user->role->assign($role);

        $this->assertTrue($user->ability->able('delete', $foo));
        $this->assertFalse($user->ability->able('delete', $bar));
    }

    /**
     * @return void
     */
    public function testEntityWithForbiddenAbility() : void
    {
        $user = User::create([
            'name' => 'John Doe',
        ]);

        $foo = User::create([
            'name' => 'Jane Doe',
        ]);

        $role = Role::create([
            'name' => 'admin',
        ]);

        $role->ability->assign(Ability::create([
            'name'        => 'delete',
            'entity_id'   => null,
            'entity_type' => User::class,
        ]), forbidden : true);

        $user->role->assign($role);

        $this->assertFalse($user->ability->able('delete'));
        $this->assertFalse($user->ability->able('delete', $foo));
        $this->assertFalse($user->ability->able('delete', User::class));
    }
}
