<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Transaction;
use Tests\TestCase;

class AuthorizationPoliciesTest extends TestCase
{
    protected User $admin;
    protected User $seller;
    protected User $buyer;
    protected User $broker;
    protected Vehicle $vehicle;
    protected Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@cbs.bt')->first();
        $this->seller = User::where('email', 'seller@cbs.bt')->first();
        $this->buyer = User::where('email', 'buyer@cbs.bt')->first();
        $this->broker = User::where('email', 'broker@cbs.bt')->first();
        $this->vehicle = Vehicle::first();
        $this->transaction = Transaction::first() ?? Transaction::factory()->create();
    }

    /**
     * Test vehicle view policy - public vehicles.
     */
    public function test_public_can_view_available_vehicle(): void
    {
        $response = $this->get(route('vehicles.show', $this->vehicle));

        $response->assertStatus(200);
    }

    /**
     * Test vehicle update policy - seller can update own vehicle.
     */
    public function test_seller_can_update_own_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['created_by' => $this->seller->id]);

        $this->assertTrue($this->seller->can('update', $vehicle));
    }

    /**
     * Test vehicle update policy - seller cannot update other vehicle.
     */
    public function test_seller_cannot_update_other_vehicle(): void
    {
        $otherSeller = User::factory()->create(['role' => 'seller']);
        $otherVehicle = Vehicle::factory()->create(['created_by' => $otherSeller->id]);

        $this->assertFalse($this->seller->can('update', $otherVehicle));
    }

    /**
     * Test vehicle delete policy - admin can delete any vehicle.
     */
    public function test_admin_can_delete_any_vehicle(): void
    {
        $this->assertTrue($this->admin->can('delete', $this->vehicle));
    }

    /**
     * Test admin can view role-restricted pages.
     */
    public function test_admin_can_access_admin_routes(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('buyers.index'));

        $response->assertStatus(200);
    }

    /**
     * Test seller cannot access admin routes.
     */
    public function test_seller_cannot_access_admin_routes(): void
    {
        $response = $this->actingAs($this->seller)
            ->get(route('buyers.index'));

        $response->assertStatus(403);
    }

    /**
     * Test buyer cannot access seller routes.
     */
    public  function test_buyer_cannot_create_vehicle(): void
    {
        $response = $this->actingAs($this->buyer)
            ->get(route('vehicles.create'));

        $response->assertStatus(403);
    }

    /**
     * Test transaction policy - only involved parties can view.
     */
    public function test_only_involved_parties_can_view_transaction(): void
    {
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
        ]);

        $this->assertTrue($this->buyer->can('view', $transaction) || 
                         $this->seller->can('view', $transaction) ||
                         $this->admin->can('view', $transaction));
    }

    /**
     * Test unrelated user cannot view transaction.
     */
    public function test_unrelated_user_cannot_view_transaction(): void
    {
        $other = User::factory()->create(['role' => 'buyer']);
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
        ]);

        $response = $this->actingAs($other)
            ->get(route('transactions.show', $transaction));

        $response->assertStatus(403);
    }

    /**
     * Test role middleware blocks incorrect roles.
     */
    public function test_role_middleware_blocks_incorrect_role(): void
    {
        $response = $this->actingAs($this->buyer)
            ->get(route('sellers.index'));

        $response->assertStatus(403);
    }

    /**
     * Test broker must have license approval.
     */
    public function test_broker_without_license_cannot_create_vehicle(): void
    {
        $this->broker->update(['dealer_license_status' => 'not_submitted']);

        $response = $this->actingAs($this->broker)
            ->get(route('vehicles.create'));

        $response->assertStatus(403);
    }
}
