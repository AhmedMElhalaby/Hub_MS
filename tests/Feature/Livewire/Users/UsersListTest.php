<?php

namespace Tests\Feature\Livewire\Users;

use App\Livewire\Users\UsersList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersListTest extends TestCase
{
    use RefreshDatabase;

    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->actingAs($this->authUser);
    }

    public function test_can_render_users_list()
    {
        User::factory()->count(3)->create();
        $response = $this->get('/users');
        $response->assertStatus(200);
    }

    public function test_can_create_user()
    {
        Livewire::test(UsersList::class)
            ->call('create')
            ->assertSet('userId', null)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'password123')
            ->call('save')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
    }

    public function test_cannot_create_user_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com'
        ]);

        Livewire::test(UsersList::class)
            ->call('create')
            ->set('name', 'John Doe')
            ->set('email', 'existing@example.com')
            ->set('password', 'password123')
            ->call('save')
            ->assertHasErrors(['email']);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();

        Livewire::test(UsersList::class)
            ->call('edit', $user->id)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
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

    public function test_can_search_users()
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);

        Livewire::test(UsersList::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    public function test_can_sort_users()
    {
        $users = User::factory()->count(3)->create();

        Livewire::test(UsersList::class)
            ->set('sortField', 'name')
            ->set('sortDirection', 'asc')
            ->assertViewHas('users');
    }
}