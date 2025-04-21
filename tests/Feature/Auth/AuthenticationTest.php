<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Volt::test('auth.login')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('remember', false);

        $component->call('login');

        $this->assertTrue(Auth::check());
        $component->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $component = Volt::test('auth.login')
            ->set('email', 'test@example.com')
            ->set('password', 'wrong-password')
            ->set('remember', false);

        $component->call('login');

        $this->assertFalse(Auth::check());
    }

    public function test_users_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertFalse(Auth::check());
        $response->assertRedirect('/');
    }

    public function test_users_can_not_make_more_than_five_attempts_in_one_minute()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        for ($i = 0; $i < 6; $i++) {
            $component = Volt::test('auth.login')
                ->set('email', 'test@example.com')
                ->set('password', 'wrong-password')
                ->set('remember', false);

            $component->call('login');
        }

        $component = Volt::test('auth.login')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('remember', false);

        $component->call('login');

        $this->assertFalse(Auth::check());
        $component->assertHasErrors(['email']);
    }
}