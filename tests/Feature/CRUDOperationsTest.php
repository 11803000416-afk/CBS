<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Vehicle;
use App\Models\Inquiry;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyerCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_buyer_index_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get('/buyers');
        $response->assertStatus(200);
        $response->assertViewIs('buyers.index');
    }

    public function test_buyer_create_form_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/buyers/create');
        $response->assertStatus(200);
        $response->assertViewIs('buyers.form');
    }

    public function test_buyer_can_be_created(): void
    {
        $data = [
            'name' => 'Test Buyer',
            'phone' => '+975-1-123456',
            'email' => 'buyer@test.bt',
            'address' => 'Test Address',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post('/buyers', $data);
        $response->assertRedirect('/buyers');
        $this->assertDatabaseHas('buyers', ['email' => 'buyer@test.bt']);
    }

    public function test_buyer_can_be_updated(): void
    {
        $buyer = Buyer::factory()->create();
        $data = ['name' => 'Updated Name', 'phone' => '+975-1-999999', 'status' => 'active'];

        $response = $this->actingAs($this->admin)->put("/buyers/{$buyer->id}", $data);
        $response->assertRedirect('/buyers');
        $this->assertDatabaseHas('buyers', ['id' => $buyer->id, 'name' => 'Updated Name']);
    }

    public function test_buyer_can_be_deleted(): void
    {
        $buyer = Buyer::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/buyers/{$buyer->id}");
        $response->assertRedirect('/buyers');
        $this->assertDatabaseMissing('buyers', ['id' => $buyer->id]);
    }

    public function test_buyer_create_validates_required_fields(): void
    {
        $data = ['name' => '', 'phone' => '', 'status' => ''];

        $response = $this->actingAs($this->admin)->post('/buyers', $data);
        $response->assertSessionHasErrors(['name', 'phone', 'status']);
    }

    public function test_buyer_email_must_be_unique(): void
    {
        Buyer::factory()->create(['email' => 'unique@test.bt']);
        
        $data = [
            'name' => 'Another Buyer',
            'phone' => '+975-1-123456',
            'email' => 'unique@test.bt',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post('/buyers', $data);
        $response->assertSessionHasErrors('email');
    }
}

class CRUDOperationsTest extends TestCase
{
    public function test_crud_operations_test_file_loads(): void
    {
        $this->assertTrue(true);
    }
}

class SellerCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_seller_index_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get('/sellers');
        $response->assertStatus(200);
        $response->assertViewIs('sellers.index');
    }

    public function test_seller_create_form_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/sellers/create');
        $response->assertStatus(200);
        $response->assertViewIs('sellers.form');
    }

    public function test_seller_can_be_created(): void
    {
        $data = [
            'name' => 'Test Seller',
            'phone' => '+975-1-123456',
            'email' => 'seller@test.bt',
            'address' => 'Test Address',
            'notes' => 'Test notes',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->post('/sellers', $data);
        $response->assertRedirect('/sellers');
        $this->assertDatabaseHas('sellers', ['email' => 'seller@test.bt']);
    }

    public function test_seller_can_be_updated(): void
    {
        $seller = Seller::factory()->create();
        $data = ['name' => 'Updated Seller', 'phone' => '+975-1-999999', 'status' => 'active'];

        $response = $this->actingAs($this->admin)->put("/sellers/{$seller->id}", $data);
        $response->assertRedirect('/sellers');
        $this->assertDatabaseHas('sellers', ['id' => $seller->id, 'name' => 'Updated Seller']);
    }

    public function test_seller_can_be_deleted(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/sellers/{$seller->id}");
        $response->assertRedirect('/sellers');
        $this->assertDatabaseMissing('sellers', ['id' => $seller->id]);
    }
}

class VehicleCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $seller;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->seller = Seller::factory()->create();
    }

    public function test_vehicle_index_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get('/vehicles');
        $response->assertStatus(200);
    }

    public function test_vehicle_can_be_created(): void
    {
        $data = [
            'seller_id' => $this->seller->id,
            'brand' => 'Toyota',
            'model' => 'Fortuner',
            'year' => 2022,
            'mileage' => 10000,
            'price' => 850000,
            'status' => 'available',
        ];

        $response = $this->actingAs($this->admin)->post('/vehicles', $data);
        $response->assertRedirect('/vehicles');
        $this->assertDatabaseHas('vehicles', ['brand' => 'Toyota', 'model' => 'Fortuner']);
    }

    public function test_vehicle_can_be_updated(): void
    {
        $vehicle = Vehicle::factory()->create(['seller_id' => $this->seller->id]);
        $data = [
            'seller_id' => $this->seller->id,
            'brand' => 'Honda',
            'model' => 'CR-V',
            'year' => 2021,
            'mileage' => 20000,
            'price' => 700000,
            'status' => 'available',
        ];

        $response = $this->actingAs($this->admin)->put("/vehicles/{$vehicle->id}", $data);
        $response->assertRedirect('/vehicles');
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'brand' => 'Honda']);
    }

    public function test_vehicle_can_be_deleted(): void
    {
        $vehicle = Vehicle::factory()->create(['seller_id' => $this->seller->id]);

        $response = $this->actingAs($this->admin)->delete("/vehicles/{$vehicle->id}");
        $response->assertRedirect('/vehicles');
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }
}

class InquiryCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $buyer;
    protected $admin;
    protected $vehicle;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->buyer = Buyer::factory()->create();
        $seller = Seller::factory()->create();
        $this->vehicle = Vehicle::factory()->create(['seller_id' => $seller->id, 'status' => 'available']);
    }

    public function test_inquiry_index_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get('/inquiries');
        $response->assertStatus(200);
    }

    public function test_inquiry_can_be_created(): void
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'message' => 'I am interested in this vehicle',
            'meeting_location' => 'Thimphu',
            'status' => 'pending',
        ];

        $response = $this->actingAs($this->admin)->post('/inquiries', $data);
        $response->assertRedirect('/inquiries');
        $this->assertDatabaseHas('inquiries', ['vehicle_id' => $this->vehicle->id]);
    }

    public function test_inquiry_can_be_updated(): void
    {
        $inquiry = Inquiry::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
        ]);

        $data = [
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'message' => 'Updated message',
            'status' => 'responded',
        ];

        $response = $this->actingAs($this->admin)->put("/inquiries/{$inquiry->id}", $data);
        $response->assertRedirect('/inquiries');
        $this->assertDatabaseHas('inquiries', ['id' => $inquiry->id, 'status' => 'responded']);
    }

    public function test_inquiry_can_be_deleted(): void
    {
        $inquiry = Inquiry::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/inquiries/{$inquiry->id}");
        $response->assertRedirect('/inquiries');
        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }
}

class TransactionCRUDTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $buyer;
    protected $seller;
    protected $vehicle;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->buyer = Buyer::factory()->create();
        $this->seller = Seller::factory()->create();
        $this->vehicle = Vehicle::factory()->create(['seller_id' => $this->seller->id]);
    }

    public function test_transaction_index_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports');
        $response->assertStatus(200);
    }

    public function test_transaction_can_be_created(): void
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'sale_price' => 750000,
            'broker_commission' => 15000,
            'status' => 'completed',
        ];

        $response = $this->actingAs($this->admin)->post('/transactions', $data);
        $response->assertRedirect('/reports');
        $this->assertDatabaseHas('transactions', ['vehicle_id' => $this->vehicle->id]);
    }

    public function test_vehicle_marked_sold_when_transaction_completed(): void
    {
        $data = [
            'vehicle_id' => $this->vehicle->id,
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'sale_price' => 750000,
            'broker_commission' => 15000,
            'status' => 'completed',
        ];

        $this->actingAs($this->admin)->post('/transactions', $data);
        $this->assertDatabaseHas('vehicles', ['id' => $this->vehicle->id, 'status' => 'sold']);
    }
}
