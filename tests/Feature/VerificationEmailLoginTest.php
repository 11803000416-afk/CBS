<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class VerificationEmailLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_login_triggers_verification_email(): void
    {
        Notification::fake();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $user = User::create([
            'name' => 'New User',
            'email' => 'new-user@example.bt',
            'phone' => '17123456',
            'address' => 'Thimphu',
            'role' => 'buyer',
            'is_active' => true,
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'new-user@example.bt',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }
}