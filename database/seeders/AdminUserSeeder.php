<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        User::updateOrCreate(
            ['email' => 'admin@cbs.bt'],
            [
                'name' => 'Admin User',
                'phone' => '17123456',
                'address' => 'Thimphu, Bhutan',
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jamyangd1995@gmail.com'],
            [
                'name' => 'Jamyang D',
                'phone' => '17123456',
                'address' => 'Thimphu, Bhutan',
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'broker@cbs.bt'],
            [
                'name' => 'Broker User',
                'phone' => '17654321',
                'address' => 'Paro, Bhutan',
                'role' => User::ROLE_AGENT,
                'is_active' => true,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        $buyer = User::updateOrCreate(
            ['email' => 'buyer@cbs.bt'],
            [
                'name' => 'Buyer Demo',
                'phone' => '17111222',
                'address' => 'Punakha, Bhutan',
                'role' => User::ROLE_BUYER,
                'is_active' => true,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        $seller = User::updateOrCreate(
            ['email' => 'seller@cbs.bt'],
            [
                'name' => 'Seller Demo',
                'phone' => '17777888',
                'address' => 'Phuentsholing, Bhutan',
                'role' => User::ROLE_SELLER,
                'is_active' => true,
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
            ]
        );

        Buyer::updateOrCreate(
            ['user_id' => $buyer->id],
            [
                'name' => $buyer->name,
                'phone' => $buyer->phone,
                'email' => $buyer->email,
                'address' => $buyer->address,
                'status' => 'active',
            ]
        );

        Seller::updateOrCreate(
            ['user_id' => $seller->id],
            [
                'name' => $seller->name,
                'phone' => $seller->phone,
                'email' => $seller->email,
                'address' => $seller->address,
                'status' => 'active',
            ]
        );
    }
}
