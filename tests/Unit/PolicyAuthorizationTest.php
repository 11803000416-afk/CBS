<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Transaction;
use App\Models\Booking;
use Tests\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    protected User $admin;
    protected User $seller;
    protected User $buyer;
    protected User $broker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->seller = User::factory()->create(['role' => 'seller']);
        $this->buyer = User::factory()->create(['role' => 'buyer']);
        $this->broker = User::factory()->create(['role' => 'broker']);
    }

    /**
     * Test VehiclePolicy view permission.
     */
    public function test_vehicle_policy_view(): void
    {
        $vehicle = Vehicle::factory()->create(['created_by' => $this->seller->id]);

        $this->assertTrue($this->seller->can('view', $vehicle));
        $this->assertTrue($this->admin->can('view', $vehicle));
        $this->assertTrue($this->buyer->can('view', $vehicle));
    }

    /**
     * Test VehiclePolicy create permission.
     */
    public function test_vehicle_policy_create(): void
    {
        $this->assertTrue($this->seller->can('create', Vehicle::class));
        $this->assertTrue($this->broker->can('create', Vehicle::class));
        $this->assertTrue($this->admin->can('create', Vehicle::class));
        $this->assertFalse($this->buyer->can('create', Vehicle::class));
    }

    /**
     * Test VehiclePolicy update permission.
     */
    public function test_vehicle_policy_update(): void
    {
        $ownVehicle = Vehicle::factory()->create(['created_by' => $this->seller->id]);
        $otherVehicle = Vehicle::factory()->create(['created_by' => User::factory()->create(['role' => 'seller'])->id]);

        $this->assertTrue($this->seller->can('update', $ownVehicle));
        $this->assertFalse($this->seller->can('update', $otherVehicle));
        $this->assertTrue($this->admin->can('update', $otherVehicle));
    }

    /**
     * Test VehiclePolicy delete permission.
     */
    public function test_vehicle_policy_delete(): void
    {
        $vehicle = Vehicle::factory()->create(['created_by' => $this->seller->id]);

        $this->assertTrue($this->admin->can('delete', $vehicle));
        $this->assertTrue($this->seller->can('delete', $vehicle));
        $this->assertFalse($this->buyer->can('delete', $vehicle));
    }

    /**
     * Test TransactionPolicy permissions.
     */
    public function test_transaction_policy(): void
    {
        $transaction = Transaction::factory()->create([
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
        ]);

        $this->assertTrue($this->buyer->can('view', $transaction));
        $this->assertTrue($this->seller->can('view', $transaction));
        $this->assertTrue($this->admin->can('view', $transaction));

        // Non-involved buyer shouldn't see it
        $otherBuyer = User::factory()->create(['role' => 'buyer']);
        $this->assertFalse($otherBuyer->can('view', $transaction));
    }

    /**
     * Test BookingPolicy permissions.
     */
    public function test_booking_policy(): void
    {
        $booking = Booking::factory()->create(['buyer_id' => $this->buyer->id]);

        $this->assertTrue($this->buyer->can('update', $booking));
        $this->assertTrue($this->buyer->can('delete', $booking));
        $this->assertTrue($this->admin->can('delete', $booking));

        $otherBuyer = User::factory()->create(['role' => 'buyer']);
        $this->assertFalse($otherBuyer->can('update', $booking));
    }
}
