<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Vehicle;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    protected User $admin;
    protected User $broker;
    protected User $seller;
    protected User $buyer;
    protected Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@cbs.bt')->first();
        $this->broker = User::where('email', 'broker@cbs.bt')->first();
        $this->seller = User::where('email', 'seller@cbs.bt')->first();
        $this->buyer = User::where('email', 'buyer@cbs.bt')->first();
        $this->vehicle = Vehicle::first();
    }

    /**
     * Test admin can list transactions.
     */
    public function test_admin_can_list_transactions(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('transactions.index'));

        $response->assertStatus(200);
    }

    /**
     * Test broker can create a transaction.
     */
    public function test_broker_can_create_transaction(): void
    {
        $response = $this->actingAs($this->broker)
            ->get(route('transactions.create'));

        $response->assertStatus(200);
    }

    /**
     * Test broker can store a transaction.
     */
    public function test_broker_can_store_transaction(): void
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'agreed_price' => 500000,
            'status' => 'pending_review',
            'payment_method' => 'bank_transfer',
            'notes' => 'Test transaction',
        ];

        $response = $this->actingAs($this->broker)
            ->post(route('transactions.store'), $data);

        $response->assertRedirect();
    }

    /**
     * Test buyer can view their own transactions.
     */
    public function test_buyer_can_view_own_transactions(): void
    {
        $transaction = Transaction::factory()->create(['buyer_id' => $this->buyer->id]);

        $response = $this->actingAs($this->buyer)
            ->get(route('transactions.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin can approve payment.
     */
    public function test_admin_can_approve_payment(): void
    {
        $transaction = Transaction::factory()->create(['status' => 'pending_review']);

        $response = $this->actingAs($this->admin)
            ->post(route('transactions.approve-payment', $transaction));

        $response->assertRedirect();
        $transaction->refresh();
        $this->assertEquals('completed', $transaction->status);
    }

    /**
     * Test admin can reject payment.
     */
    public function test_admin_can_reject_payment(): void
    {
        $transaction = Transaction::factory()->create(['status' => 'pending_review']);

        $response = $this->actingAs($this->admin)
            ->post(route('transactions.reject-payment', $transaction), [
                'rejection_reason' => 'Invalid payment',
            ]);

        $response->assertRedirect();
    }

    /**
     * Test rate limiting on transaction creation.
     */
    public function test_transaction_creation_is_rate_limited(): void
    {
        // Make 11 requests (limit is 10 per minute)
        for ($i = 0; $i < 11; $i++) {
            $data = [
                'vehicle_id' => $this->vehicle->id,
                'buyer_id' => $this->buyer->id,
                'seller_id' => $this->seller->id,
                'agreed_price' => 500000,
                'status' => 'pending_review',
                'payment_method' => 'bank_transfer',
            ];

            $response = $this->actingAs($this->broker)
                ->post(route('transactions.store'), $data);

            if ($i < 10) {
                $response->assertStatus(302); // First 10 should redirect
            } else {
                $response->assertStatus(429); // 11th should be rate limited
            }
        }
    }

    /**
     * Test unauthorized user cannot access transactions.
     */
    public function test_guest_cannot_access_transactions(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertRedirect(route('login'));
    }
}
