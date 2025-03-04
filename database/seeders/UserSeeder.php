<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'user_type' => 'passenger',
        ]);

        // Create passengers
        User::factory()->count(10)->passenger()->create();
        
        // Create drivers
        User::factory()->count(5)->driver()->create();
    }
}