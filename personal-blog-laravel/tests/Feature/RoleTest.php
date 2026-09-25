<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $writer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->writer = User::factory()->create();
        $this->writer->assignRole('writer');
    }

    public function test_index_should_show_roles_except_admin()
    {
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($this->admin)->get(route('roles.index'));

        $response->assertStatus(200);
        $response->assertViewHas('roles', function ($collection) use ($role) {
            return $collection->contains($role);
        });
        $response->assertViewHas('roles', function ($collection) {
            $adminRole = Role::where('name', 'admin')->first();

            return ! $collection->contains($adminRole);
        });
    }

    public function test_index_should_be_forbidden_for_non_admin()
    {
        $response = $this->actingAs($this->writer)->get(route('roles.index'));

        $response->assertStatus(403);
    }

    public function test_create_should_return_view_with_permissions()
    {
        $response = $this->actingAs($this->admin)->get(route('roles.create'));

        $response->assertStatus(200);
        $response->assertViewHas('permissions');
    }

    public function test_store_should_create_role_and_permissions()
    {
        $permission = Permission::first() ?? Permission::create(['name' => 'edit articles']);

        $roleData = [
            'name' => 'Moderator',
            'permissions' => [$permission->name],
        ];

        $response = $this->actingAs($this->admin)->post(route('roles.store'), $roleData);

        $response->assertStatus(302);
        $response->assertRedirect(route('roles.index'));
        $response->assertSessionHas('success', 'Role created successfully!');
        $this->assertDatabaseHas('roles', ['name' => 'moderator']);

        $role = Role::where('name', 'moderator')->first();
        $this->assertTrue($role->hasPermissionTo($permission));
    }

    public function test_edit_should_return_view_with_role_and_permissions()
    {
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($this->admin)->get(route('roles.edit', $role));

        $response->assertStatus(200);
        $response->assertViewHas('role', $role);
        $response->assertViewHas('permissions');
    }

    public function test_update_should_update_role_and_permissions()
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::first() ?? Permission::create(['name' => 'edit articles']);

        $updatedData = [
            'name' => 'Super Editor',
            'permissions' => [$permission->name],
        ];

        $response = $this->actingAs($this->admin)->put(route('roles.update', $role), $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect(route('roles.index'));
        $response->assertSessionHas('success', 'Role updated successfully!');
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Super Editor']);
        $this->assertTrue($role->fresh()->hasPermissionTo($permission));
    }

    public function test_destroy_should_delete_role()
    {
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($this->admin)->delete(route('roles.destroy', $role));

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Role deleted succesfully!');
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
