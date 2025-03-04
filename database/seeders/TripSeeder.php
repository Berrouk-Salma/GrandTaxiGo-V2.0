<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run()
    {
        // Create trips with various statuses
        Trip::factory()->count(20)->create();
        
        // Create some pending trips specifically
        Trip::factory()->count(10)->pending()->create();
    }
}