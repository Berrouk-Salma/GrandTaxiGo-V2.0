<?php

namespace Database\Seeders;

use App\Models\DriverAvailability;
use Illuminate\Database\Seeder;

class DriverAvailabilitySeeder extends Seeder
{
    public function run()
    {
        DriverAvailability::factory()->count(15)->create();
    }
}