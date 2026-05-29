<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Create sellers
        $seller1 = Seller::firstOrCreate(
            ['email' => 'john.smith@example.bt'],
            [
                'name' => 'John Smith',
                'phone' => '+975-1-123456',
                'address' => 'Thimphu',
                'status' => 'active',
            ]
        );

        $seller2 = Seller::firstOrCreate(
            ['email' => 'dorji.wangchuk@example.bt'],
            [
                'name' => 'Dorji Wangchuk',
                'phone' => '+975-1-234567',
                'address' => 'Paro',
                'status' => 'active',
            ]
        );

        // Create sample vehicles
        Vehicle::firstOrCreate(
            ['brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2022],
            [
                'seller_id' => $seller1->id,
                'created_by' => 1,
                'mileage' => 15000,
                'price' => 850000,
                'description' => 'Well maintained SUV, service records available',
                'status' => 'available',
                'images' => [],
            ]
        );

        Vehicle::firstOrCreate(
            ['brand' => 'Hyundai', 'model' => 'Creta', 'year' => 2021],
            [
                'seller_id' => $seller1->id,
                'created_by' => 1,
                'mileage' => 22000,
                'price' => 620000,
                'description' => 'Compact SUV, excellent condition',
                'status' => 'available',
                'images' => [],
            ]
        );

        Vehicle::firstOrCreate(
            ['brand' => 'Maruti', 'model' => 'Swift', 'year' => 2020],
            [
                'seller_id' => $seller2->id,
                'created_by' => 1,
                'mileage' => 35000,
                'price' => 380000,
                'description' => 'Reliable hatchback, good fuel efficiency',
                'status' => 'available',
                'images' => [],
            ]
        );

        // Buyers are created via migration/user registration
        Buyer::firstOrCreate(
            ['email' => 'buyer@example.bt'],
            [
                'name' => 'Test Buyer',
                'phone' => '+975-1-555555',
                'address' => 'Thimphu',
                'status' => 'active',
            ]
        );
    }
}
