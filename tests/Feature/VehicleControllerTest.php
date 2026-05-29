<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;

class VehicleControllerTest extends TestCase
{
    protected User $seller;
    protected User $admin;
    protected Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seller = User::where('email', 'seller@cbs.bt')->first();
        $this->admin = User::where('email', 'admin@cbs.bt')->first();
        $this->vehicle = Vehicle::first();
    }

    /**
     * Test admin can view all vehicles.
     */
    public function test_admin_can_list_vehicles(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('vehicles.index'));

        $response->assertStatus(200);
    }

    /**
     * Test seller can create a vehicle.
     */
    public function test_seller_can_create_vehicle(): void
    {
        $response = $this->actingAs($this->seller)
            ->get(route('vehicles.create'));

        $response->assertStatus(200);
    }

    /**
     * Test seller can store a vehicle.
     */
    public function test_seller_can_store_vehicle(): void
    {
        $data = [
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'price' => 50000,
            'mileage' => 45000,
            'condition' => 'good',
            'features' => 'Air conditioning, Power steering',
            'description' => 'Well-maintained vehicle',
            'status' => 'available',
        ];

        $response = $this->actingAs($this->seller)
            ->post(route('vehicles.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('vehicles', ['brand' => 'Toyota']);
    }

    /**
     * Test seller can edit their own vehicle.
     */
    public function test_seller_can_edit_own_vehicle(): void
    {
        $response = $this->actingAs($this->seller)
            ->get(route('vehicles.edit', $this->vehicle));

        $response->assertStatus(200);
    }

    /**
     * Test seller can update their own vehicle.
     */
    public function test_seller_can_update_own_vehicle(): void
    {
        $data = ['price' => 60000, 'status' => 'reserved'];

        $response = $this->actingAs($this->seller)
            ->put(route('vehicles.update', $this->vehicle), $data);

        $response->assertRedirect();
    }

    /**
     * Test seller cannot edit another seller's vehicle.
     */
    public function test_seller_cannot_edit_others_vehicle(): void
    {
        $otherSeller = User::factory()->create(['role' => 'seller']);
        $otherVehicle = Vehicle::factory()->create(['created_by' => $otherSeller->id]);

        $response = $this->actingAs($this->seller)
            ->get(route('vehicles.edit', $otherVehicle));

        $response->assertStatus(403);
    }

    /**
     * Test public can browse vehicles.
     */
    public function test_public_can_browse_vehicles(): void
    {
        $response = $this->get(route('vehicles.unified'));

        $response->assertStatus(200);
    }

    /**
     * Test unauthorized user cannot delete vehicle.
     */
    public function test_unauthorized_user_cannot_delete_vehicle(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)
            ->delete(route('vehicles.destroy', $this->vehicle));

        $response->assertStatus(403);
    }
}
