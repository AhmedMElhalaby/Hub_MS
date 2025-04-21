<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;
    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->actingAs($this->authUser, 'sanctum');
    }

    public function test_can_list_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'created_at'
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'current_page',
                        'per_page',
                        'last_page',
                        'from',
                        'to'
                    ]
                ]
            ]);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'name' => $userData['name'],
                    'email' => $userData['email']
                ]
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    public function test_cannot_create_user_with_existing_email()
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => fake()->name(),
            'email' => $existingUser->email,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $updatedData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'name' => $updatedData['name'],
                    'email' => $updatedData['email']
                ]
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $updatedData['email']
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}