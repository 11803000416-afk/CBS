<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleReviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $buyerUser;
    protected User $sellerUser;
    protected User $brokerUser;
    protected Buyer $buyer;
    protected Seller $seller;
    protected Vehicle $vehicle;
    protected Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->buyerUser = User::create([
            'name' => 'Review Buyer',
            'email' => 'review-buyer@example.bt',
            'phone' => '+97517111111',
            'address' => 'Thimphu',
            'role' => 'buyer',
            'is_active' => true,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $this->sellerUser = User::create([
            'name' => 'Review Seller',
            'email' => 'review-seller@example.bt',
            'phone' => '+97517222222',
            'address' => 'Paro',
            'role' => 'seller',
            'is_active' => true,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $this->brokerUser = User::create([
            'name' => 'Review Broker',
            'email' => 'review-broker@example.bt',
            'phone' => '+97517333333',
            'address' => 'Phuentsholing',
            'role' => 'broker',
            'is_active' => true,
            'dealer_license_status' => 'approved',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $this->buyer = Buyer::create([
            'user_id' => $this->buyerUser->id,
            'name' => $this->buyerUser->name,
            'phone' => $this->buyerUser->phone,
            'email' => $this->buyerUser->email,
            'address' => $this->buyerUser->address,
            'status' => 'active',
        ]);

        $this->seller = Seller::create([
            'user_id' => $this->sellerUser->id,
            'name' => $this->sellerUser->name,
            'phone' => $this->sellerUser->phone,
            'email' => $this->sellerUser->email,
            'address' => $this->sellerUser->address,
            'status' => 'active',
        ]);

        $this->vehicle = Vehicle::create([
            'seller_id' => $this->seller->id,
            'created_by' => $this->brokerUser->id,
            'brand' => 'Toyota',
            'model' => 'Fortuner',
            'year' => 2022,
            'mileage' => 15000,
            'price' => 850000,
            'currency' => 'Nu.',
            'description' => 'Test vehicle',
            'images' => [],
            'videos' => [],
            'status' => 'sold',
            'transmission' => 'Automatic',
            'fuel_type' => 'Diesel',
        ]);

        $this->transaction = Transaction::create([
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'broker_id' => $this->brokerUser->id,
            'sale_price' => 850000,
            'broker_commission' => 25000,
            'currency' => 'Nu.',
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => 'Completed sale',
        ]);
    }

    public function test_verified_buyer_can_submit_vehicle_review(): void
    {
        $response = $this->actingAs($this->buyerUser)->post(route('vehicle-reviews.store', $this->vehicle), [
            'rating' => 5,
            'title' => 'Excellent purchase',
            'comment' => 'Great vehicle and smooth process.',
            'pros' => 'Excellent condition',
            'cons' => 'None',
            'would_recommend' => true,
        ]);

        $response->assertRedirect(route('vehicles.show', $this->vehicle));
        $this->assertDatabaseHas('vehicle_reviews', [
            'vehicle_id' => $this->vehicle->id,
            'transaction_id' => $this->transaction->id,
            'reviewer_id' => $this->buyerUser->id,
            'rating' => 5,
        ]);
    }

    public function test_buyer_cannot_review_same_transaction_twice(): void
    {
        VehicleReview::create([
            'vehicle_id' => $this->vehicle->id,
            'transaction_id' => $this->transaction->id,
            'reviewer_id' => $this->buyerUser->id,
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'rating' => 4,
            'title' => 'Nice',
            'comment' => 'Good car',
            'would_recommend' => true,
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->buyerUser)->post(route('vehicle-reviews.store', $this->vehicle), [
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('review');
    }

    public function test_non_buyer_cannot_submit_review(): void
    {
        $guestSeller = User::create([
            'name' => 'Guest Seller',
            'email' => 'guest-seller@example.bt',
            'phone' => '+97517444444',
            'address' => 'Thimphu',
            'role' => 'seller',
            'is_active' => true,
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($guestSeller)->post(route('vehicle-reviews.store', $this->vehicle), [
            'rating' => 4,
        ]);

        $response->assertStatus(403);
    }
}
