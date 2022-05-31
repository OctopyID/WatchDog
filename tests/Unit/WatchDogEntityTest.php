<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\Permission;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogEntityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testEntityHaveRole() : void
    {
        $user = User::create([
            'name' => 'John Doe',
        ]);

        $role = Role::create([
            'name' => 'foo',
        ]);

        $user->role->assign($role);

        $this->assertTrue($user->role->has($role));
        $this->assertTrue($user->role->has('foo'));

        $this->assertTrue($user->role->has([
            'foo',
            'bar',
        ]));

        $this->assertFalse($user->role->has('bar'));
    }

    /**
     * @return void
     */
    public function testEntityHaveBasicAbility() : void
    {
        $user = User::create([
            'name' => 'foo',
        ]);

        $ability = Ability::create([
            'name' => 'bar',
        ]);

        $user->ability->assign($ability);

        $this->assertDatabaseHas(Permission::class, [
            'entity_id'   => $user->id,
            'ability_id'  => $ability->id,
            'entity_type' => User::class,
        ]);

        $this->assertTrue($user->ability->able('bar'));
    }

    /**
     * @return void
     */
    public function testEntityHaveModelAbilities() : void
    {
        $foo = User::create([
            'name' => 'foo',
        ]);

        $bar = User::create([
            'name' => 'bar',
        ]);

        $ability = Ability::create([
            'name'        => 'edit',
            'entity_type' => Ability::class,
        ]);

        $foo->ability->assign($ability);

        $this->assertDatabaseHas(Permission::class, [
            'entity_id'   => $foo->id,
            'ability_id'  => $ability->id,
            'entity_type' => User::class,
        ]);

        $this->assertTrue($foo->ability->able('edit', Ability::class));
        $this->assertFalse($bar->ability->able('edit', Ability::class));
    }

    /**
     * @return void
     */
    public function testEntityHaveRecordAbilities() : void
    {
        $role = Role::create([
            'name' => 'admin',
        ]);

        $foo = User::create([
            'name' => 'Supian M',
        ]);

        $bar = User::create([
            'name' => 'John Doe',
        ]);

        $ability = Ability::create([
            'name'        => 'edit',
            'entity_id'   => $bar->id,
            'entity_type' => User::class,
        ]);

        $foo->ability->assign($ability);

        $this->assertTrue($foo->ability->able('edit', $bar));
        $this->assertFalse($foo->ability->able('edit', $foo));
        $this->assertFalse($foo->ability->able('edit', User::class));

        $this->assertFalse($bar->ability->able('edit', $foo));
        $this->assertFalse($bar->ability->able('edit', $bar));
        $this->assertFalse($bar->ability->able('edit', User::class));
    }

    /**
     * @return void
     */
    public function testEntityHaveBasicAbilityByRole() : void
    {
        $role = Role::create([
            'name' => 'foo',
        ]);

        $user = User::create([
            'name' => 'Supian M',
        ]);

        $ability = Ability::create([
            'name' => 'edit',
        ]);

        $user->role->assign($role);
        $role->ability->assign($ability);

        $this->assertTrue($user->ability->able('edit'));
    }

    /**
     * @return void
     */
    public function testEntityHaveModelAbilitiesByRole() : void
    {
        $role = Role::create([
            'name' => 'foo',
        ]);

        $user = User::create([
            'name' => 'Supian M',
        ]);

        $ability = Ability::create([
            'name'        => 'edit',
            'entity_type' => Ability::class,
        ]);

        $user->role->assign($role);
        $role->ability->assign($ability);

        $this->assertTrue($user->ability->able('edit', Ability::class));
    }

    /**
     * @return void
     */
    public function testEntityHaveRecordAbilitiesByRole() : void
    {
        $role = Role::create([
            'name' => 'admin',
        ]);

        $foo = User::create([
            'name' => 'Supian M',
        ]);

        $bar = User::create([
            'name' => 'John Doe',
        ]);

        $ability = Ability::create([
            'name'        => 'edit',
            'entity_id'   => $bar->id,
            'entity_type' => User::class,
        ]);

        $foo->role->assign($role);
        $role->ability->assign($ability);

        $this->assertTrue($foo->ability->able('edit', $bar));
        $this->assertFalse($foo->ability->able('edit', $foo));
        $this->assertFalse($foo->ability->able('edit', User::class));

        $this->assertFalse($bar->ability->able('edit', $foo));
        $this->assertFalse($bar->ability->able('edit', $bar));
        $this->assertFalse($bar->ability->able('edit', User::class));
    }
}
