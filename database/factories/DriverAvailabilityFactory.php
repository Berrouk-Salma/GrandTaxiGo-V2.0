<?php

namespace Database\Factories;

use App\Models\DriverAvailability;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverAvailabilityFactory extends Factory
{
    protected $model = DriverAvailability::class;

    public function definition()
    {
        $driver = User::where('user_type', 'driver')->inRandomOrder()->first();
        $startTime = $this->faker->dateTimeBetween('now', '+1 week');
        $endTime = $this->faker->dateTimeBetween($startTime, '+1 day');
        
        return [
            'driver_id' => $driver->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'current_location' => $this->faker->city(),
            'status' => $this->faker->randomElement(['available', 'unavailable']),
        ];
    }
}