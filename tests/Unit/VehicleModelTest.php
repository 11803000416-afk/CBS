<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;

class VehicleModelTest extends TestCase
{
    protected User $seller;
    protected Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seller = User::factory()->create(['role' => 'seller']);
        $this->vehicle = Vehicle::factory()->create(['created_by' => $this->seller->id]);
    }

    /**
     * Test vehicle has seller relationship.
     */
    public function test_vehicle_has_seller(): void
    {
        $this->assertNotNull($this->vehicle->seller);
        $this->assertEquals($this->seller->id, $this->vehicle->seller->id);
    }

    /**
     * Test vehicle has created_by user.
     */
    public function test_vehicle_has_created_by_user(): void
    {
        $this->assertNotNull($this->vehicle->creator);
        $this->assertEquals($this->seller->id, $this->vehicle->creator->id);
    }

    /**
     * Test vehicle status is available by default.
     */
    public function test_vehicle_status_available_by_default(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->assertEquals('available', $vehicle->status);
    }

    /**
     * Test vehicle can be marked as sold.
     */
    public function test_vehicle_can_be_marked_sold(): void
    {
        $this->vehicle->update(['status' => 'sold']);

        $this->assertEquals('sold', $this->vehicle->status);
    }

    /**
     * Test vehicle can be marked as reserved.
     */
    public function test_vehicle_can_be_marked_reserved(): void
    {
        $this->vehicle->update(['status' => 'reserved']);

        $this->assertEquals('reserved', $this->vehicle->status);
    }

    /**
     * Test vehicle price is stored correctly.
     */
    public function test_vehicle_price_is_stored(): void
    {
        $vehicle = Vehicle::factory()->create(['price' => 500000]);

        $this->assertEquals(500000, $vehicle->price);
    }

    /**
     * Test vehicle has brand and model.
     */
    public function test_vehicle_has_brand_and_model(): void
    {
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Toyota',
            'model' => 'Corolla',
        ]);

        $this->assertEquals('Toyota', $vehicle->brand);
        $this->assertEquals('Corolla', $vehicle->model);
    }

    /**
     * Test vehicle year is within valid range.
     */
    public function test_vehicle_year_is_valid(): void
    {
        $vehicle = Vehicle::factory()->create(['year' => 2020]);

        $this->assertGreaterThanOrEqual(1900, $vehicle->year);
        $this->assertLessThanOrEqual(now()->year + 1, $vehicle->year);
    }

    /**
     * Test vehicle has mileage.
     */
    public function test_vehicle_has_mileage(): void
    {
        $vehicle = Vehicle::factory()->create(['mileage' => 45000]);

        $this->assertEquals(45000, $vehicle->mileage);
    }

    /**
     * Test vehicle can have broker assigned.
     */
    public function test_vehicle_can_have_broker(): void
    {
        $broker = User::factory()->create(['role' => 'broker']);
        $vehicle = Vehicle::factory()->create(['broker_id' => $broker->id]);

        $this->assertEquals($broker->id, $vehicle->broker_id);
    }
}
