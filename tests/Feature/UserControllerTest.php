<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->post(route('admin.user.store'), $userData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $response->assertSessionHas('success', 'User was created successfully');
    }

    public function test_can_read_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.user.edit', ['id' => $user->id]));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '9876543210',
            'password' => 'updatedpassword',
        ];

        $response = $this->post(route('admin.user.update', ['id' => $user->id]), $updatedData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $response->assertSessionHas('success', 'User was updated successfully');
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.user.destroy', ['id' => $user->id]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $response->assertSessionHas('success', 'User was deleted successfully');
    }
}