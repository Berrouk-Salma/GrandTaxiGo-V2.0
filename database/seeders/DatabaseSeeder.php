<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'user_type' => 'passenger',
            'role' => 'admin',
            'profile_image' => 'profiles/default.jpg',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->call([
            UserSeeder::class,
            TripSeeder::class,
            DriverAvailabilitySeeder::class,
            ReviewSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}