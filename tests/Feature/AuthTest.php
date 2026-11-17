<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_a_regular_user_and_never_an_admin(): void
    {
        $this->post(route('register.action'), [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'admin', // percobaan privilege escalation
        ])->assertRedirect(route('home'));

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $this->assertSame('user', $user->role);
        $this->assertFalse($user->isAdmin());
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_validates_input(): void
    {
        $this->post(route('register.action'), ['email' => 'bukan-email', 'password' => '123'])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_login_redirects_admin_to_dashboard_and_user_to_home(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'role' => 'admin']);
        User::factory()->create(['email' => 'user@example.com', 'role' => 'user']);

        $this->post(route('login.action'), ['email' => 'admin@example.com', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login.action'), ['email' => 'user@example.com', 'password' => 'password'])
            ->assertRedirect(route('home'));
    }

    public function test_login_with_wrong_password_fails(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        $this->from(route('login'))
            ->post(route('login.action'), ['email' => 'user@example.com', 'password' => 'salah'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_ends_the_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_logged_in_user_cannot_open_login_page(): void
    {
        $this->actingAs(User::factory()->create())->get(route('login'))->assertRedirect();
    }
}
