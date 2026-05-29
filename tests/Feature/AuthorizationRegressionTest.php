<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_buyers_page(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => true]);

        $this->actingAs($admin)
            ->get('/buyers')
            ->assertOk();
    }

    public function test_buyer_is_redirected_from_admin_buyers_page(): void
    {
        $buyer = User::factory()->create(['role' => User::ROLE_BUYER, 'is_active' => true]);

        $this->actingAs($buyer)
            ->get('/buyers')
            ->assertRedirect(route('dashboard'));
    }

    public function test_seller_is_redirected_from_admin_seller_requests_page(): void
    {
        $seller = User::factory()->create(['role' => User::ROLE_SELLER, 'is_active' => true]);

        $this->actingAs($seller)
            ->get('/seller-requests')
            ->assertRedirect(route('dashboard'));
    }

    public function test_broker_is_treated_as_seller_for_role_protected_vehicle_routes(): void
    {
        $broker = User::factory()->create(['role' => User::ROLE_BROKER, 'is_active' => true]);

        $this->actingAs($broker)
            ->get('/vehicles')
            ->assertOk();
    }
}
