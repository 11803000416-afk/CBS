<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    protected User $buyer;
    protected User $seller;
    protected Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->buyer = User::where('email', 'buyer@cbs.bt')->first();
        $this->seller = User::where('email', 'seller@cbs.bt')->first();
        $this->vehicle = Vehicle::first();
    }

    /**
     * Test buyer can view bookings.
     */
    public function test_buyer_can_list_bookings(): void
    {
        $response = $this->actingAs($this->buyer)
            ->get(route('bookings.index'));

        $response->assertStatus(200);
    }

    /**
     * Test buyer can create booking.
     */
    public function test_buyer_can_create_booking(): void
    {
        $response = $this->actingAs($this->buyer)
            ->get(route('bookings.create', $this->vehicle));

        $response->assertStatus(200);
    }

    /**
     * Test buyer can store booking.
     */
    public function test_buyer_can_store_booking(): void
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '14:00',
            'notes' => 'Interested in test drive',
        ];

        $response = $this->actingAs($this->buyer)
            ->post(route('bookings.store', $this->vehicle), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'buyer_id' => $this->buyer->id,
            'vehicle_id' => $this->vehicle->id,
        ]);
    }

    /**
     * Test buyer can view their booking.
     */
    public function test_buyer_can_view_own_booking(): void
    {
        $booking = Booking::factory()->create(['buyer_id' => $this->buyer->id]);

        $response = $this->actingAs($this->buyer)
            ->get(route('bookings.show', $booking));

        $response->assertStatus(200);
    }

    /**
     * Test buyer can update their booking.
     */
    public function test_buyer_can_update_own_booking(): void
    {
        $booking = Booking::factory()->create(['buyer_id' => $this->buyer->id]);

        $data = ['booking_date' => now()->addDays(2)->toDateString()];

        $response = $this->actingAs($this->buyer)
            ->put(route('bookings.update', $booking), $data);

        $response->assertRedirect();
    }

    /**
     * Test buyer can cancel their booking.
     */
    public function test_buyer_can_cancel_booking(): void
    {
        $booking = Booking::factory()->create(['buyer_id' => $this->buyer->id]);

        $response = $this->actingAs($this->buyer)
            ->delete(route('bookings.destroy', $booking));

        $response->assertRedirect();
    }

    /**
     * Test buyer cannot modify another buyer's booking.
     */
    public function test_buyer_cannot_modify_other_booking(): void
    {
        $otherBuyer = User::factory()->create(['role' => 'buyer']);
        $booking = Booking::factory()->create(['buyer_id' => $otherBuyer->id]);

        $response = $this->actingAs($this->buyer)
            ->put(route('bookings.update', $booking), ['booking_date' => now()->toDateString()]);

        $response->assertStatus(403);
    }

    /**
     * Test guest cannot create booking.
     */
    public function test_guest_cannot_create_booking(): void
    {
        $response = $this->get(route('bookings.create', $this->vehicle));

        $response->assertRedirect(route('login'));
    }
}
