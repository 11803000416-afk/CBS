<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('email', 'tester@example.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'tester@example.com',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created user: tester@example.com');
        } else {
            $this->command->info('User already exists: tester@example.com');
        }
    }
}
