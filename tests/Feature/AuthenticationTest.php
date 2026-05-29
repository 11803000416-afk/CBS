<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test user can view login page.
     */
    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test user can view register page.
     */
    public function test_user_can_view_register_page(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test user can register.
     */
    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'phone' => '+97517123456',
            'role' => 'buyer',
            'password' => 'password@123',
            'password_confirmation' => 'password@123',
        ];

        $response = $this->post('/register', $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    /**
     * Test user can login with correct credentials.
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::where('email', 'admin@cbs.bt')->first();

        $response = $this->post('/login', [
            'email' => 'admin@cbs.bt',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with incorrect credentials.
     */
    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@cbs.bt',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test login attempt is throttled.
     */
    public function test_login_attempts_are_throttled(): void
    {
        // Make 7 failed login attempts (throttle limit is 6  per minute)
        for ($i = 0; $i < 7; $i++) {
            $response = $this->post('/login', [
                'email' => 'admin@cbs.bt',
                'password' => 'wrong-password',
            ]);

            if ($i < 6) {
                $response->assertRedirect();
            } else {
                $response->assertStatus(429); // Rate limited
            }
        }
    }

    /**
     * Test user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@cbs.bt')->first();

        $response = $this->actingAs($user)
            ->post('/logout');

        $response->assertRedirect();
        $this->assertGuest();
    }

    /**
     * Test inactive user cannot login.
     */
    public function test_inactive_user_cannot_login(): void
    {
        $user = User::where('email', 'seller@cbs.bt')->first();
        $user->update(['is_active' => false]);

        $response = $this->post('/login', [
            'email' => 'seller@cbs.bt',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test authenticated user cannot access login page.
     */
    public function test_authenticated_user_cannot_access_login(): void
    {
        $user = User::where('email', 'admin@cbs.bt')->first();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect(route('dashboard'));
    }
}
