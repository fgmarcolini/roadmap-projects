<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_index_should_show_users_except_authenticated()
    {
        $otherUser = User::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users', function ($collection) use ($otherUser) {
            return $collection->contains($otherUser);
        });
        $response->assertViewHas('users', function ($collection) {
            return ! $collection->contains(Auth::user());
        });
    }

    public function test_index_should_be_unauthorized_for_non_admin_users()
    {
        $response = $this->actingAs($this->writer)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_create_should_return_view_with_roles_if_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewHas('roles');
        $response->assertSee('Create');
    }

    public function test_create_should_be_unauthorized_for_non_admin_users()
    {
        $response = $this->actingAs($this->writer)->get(route('users.create'));

        $response->assertStatus(403);
    }

    public function test_store_should_save_user_and_assign_role_and_redirect_if_valid_data()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'writer',
        ];

        $response = $this->actingAs($this->admin)->post(route('users.store'), $userData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User created successfully!');
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        $newUser = User::where('email', 'john@example.com')->first();
        expect($newUser->hasRole('writer'))->toBeTrue();
    }

    public function test_edit_should_return_view_with_user_and_roles_if_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
        $response->assertViewHas('roles');
    }

    public function test_update_should_update_user_and_roles_and_redirect()
    {
        $user = User::factory()->create();
        $user->assignRole('writer');

        $updatedData = [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'admin',
        ];

        $response = $this->actingAs($this->admin)->put(route('users.update', $user), $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully!');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        expect($user->fresh()->hasRole('admin'))->toBeTrue();
    }

    public function test_destroy_should_delete_user_if_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $user));

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'User deleted succesfully!');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
