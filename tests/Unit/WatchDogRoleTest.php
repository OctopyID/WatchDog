<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Octopy\WatchDog\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Octopy\WatchDog\Models\Ability;
use Octopy\WatchDog\Models\AssignedRole;
use Octopy\WatchDog\Models\Permission;
use Octopy\WatchDog\Models\Role;
use Octopy\WatchDog\Tests\TestCase;
use Octopy\WatchDog\Tests\User;

class WatchDogRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testRoleHaveBasicAbilities() : void
    {
        $role = Role::create([
            'name' => 'foo',
        ]);

        $ability = Ability::create([
            'name' => 'bar',
        ]);

        $role->ability->assign($ability);

        $this->assertDatabaseHas(Permission::class, [
            'entity_id'   => $role->id,
            'ability_id'  => $ability->id,
            'entity_type' => Role::class,
        ]);

        $this->assertTrue($role->ability->able('bar'));
    }

    /**
     * @return void
     */
    public function testRoleHaveModelAbilities() : void
    {
        $foo = Role::create([
            'name' => 'foo',
        ]);

        $bar = Role::create([
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
            'entity_type' => Role::class,
        ]);

        $this->assertTrue($foo->ability->able('edit', Ability::class));
        $this->assertFalse($bar->ability->able('edit', Ability::class));
    }

    /**
     * @return void
     */
    public function testRoleHaveRecordAbilities() : void
    {
        $foo = Role::create([
            'name' => 'foo',
        ]);

        $bar = Role::create([
            'name' => 'bar',
        ]);

        $ability = Ability::create([
            'name'        => 'edit',
            'entity_id'   => 1,
            'entity_type' => Ability::class,
        ]);

        $foo->ability->assign($ability);

        $this->assertDatabaseHas(Permission::class, [
            'entity_id'   => $foo->id,
            'ability_id'  => $ability->id,
            'entity_type' => Role::class,
        ]);

        $this->assertTrue($foo->ability->able('edit', $ability));
        $this->assertFalse($bar->ability->able('edit', $ability));
    }

    /**
     * @return void
     */
    public function testItCanAssignRole() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $foo = Role::create([
            'name' => 'foo',
        ]);

        $bar = Role::create([
            'name' => 'bar',
        ]);

        // Direct
        $user->role->assign($foo);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $user->role->retract($foo);

        // String
        $user->role->assign('foo|bar');

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);

        $user->role->retract($foo);
        $user->role->retract($bar);

        // Arrayable
        $user->role->assign([
            $foo, $bar,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);
    }

    /**
     * @return void
     */
    public function testItCanRetractRole() : void
    {
        $user = User::create([
            'name' => 'Supian M',
        ]);

        $foo = Role::create([
            'name' => 'foo',
        ]);

        $bar = Role::create([
            'name' => 'bar',
        ]);

        // Direct
        $user->role->assign($foo);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $user->role->retract($foo);

        $this->assertDatabaseMissing(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        // String
        $user->role->assign('foo|bar');

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);

        $user->role->retract($foo);
        $user->role->retract($bar);

        $this->assertDatabaseMissing(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseMissing(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);

        // Arrayable
        $user->role->assign([
            $foo, $bar,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseHas(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);

        $user->role->retract([
            $foo, $bar,
        ]);

        $this->assertDatabaseMissing(AssignedRole::class, [
            'role_id'   => $foo->id,
            'entity_id' => $user->id,
        ]);

        $this->assertDatabaseMissing(AssignedRole::class, [
            'role_id'   => $bar->id,
            'entity_id' => $user->id,
        ]);
    }
}
