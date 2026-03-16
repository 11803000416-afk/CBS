<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cbs.bt',
            'phone' => '17123456',
            'address' => 'Thimphu, Bhutan',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        // Create Agent user
        User::create([
            'name' => 'Agent User',
            'email' => 'agent@cbs.bt',
            'phone' => '17654321',
            'address' => 'Paro, Bhutan',
            'role' => 'agent',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        // Create Buyer user
        User::create([
            'name' => 'Buyer Demo',
            'email' => 'buyer@cbs.bt',
            'phone' => '17111222',
            'address' => 'Punakha, Bhutan',
            'role' => 'buyer',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);
    }
}
