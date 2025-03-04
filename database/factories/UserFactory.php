<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $userType = $this->faker->randomElement(['passenger', 'driver']);
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'user_type' => $userType,
            'profile_image' => 'profiles/default.jpg',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function passenger()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'passenger',
            ];
        });
    }

    public function driver()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'driver',
            ];
        });
    }
}