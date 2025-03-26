<?php

namespace Tests\Feature\Livewire\Users;

use App\Livewire\Users\UsersList;
use App\Livewire\Users\UserDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersLivewireTest extends TestCase
{
    use RefreshDatabase;

    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->actingAs($this->authUser);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password'
        ];

        Livewire::test(UsersList::class)
            ->set('name', $userData['name'])
            ->set('email', $userData['email'])
            ->set('password', $userData['password'])
            ->call('save')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $updatedData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password'
        ];

        Livewire::test(UsersList::class)
            ->call('edit', $user->id)
            ->set('name', $updatedData['name'])
            ->set('email', $updatedData['email'])
            ->set('password', $updatedData['password'])
            ->call('save')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updatedData['name'],
            'email' => $updatedData['email']
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();

        Livewire::test(UsersList::class)
            ->call('confirmDelete', $user->id)
            ->call('delete')
            ->assertDispatched('notify');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_redirects_for_non_existent_user()
    {
        try {
            Livewire::test(UserDetails::class, ['user' => 9999]);
            $this->fail('Expected exception was not thrown');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->assertTrue(true); // Exception was thrown as expected
        }
    }
}