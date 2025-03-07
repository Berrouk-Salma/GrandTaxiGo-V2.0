<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Create successful payments
        Payment::factory()->count(30)->succeeded()->create();
        
        // Create pending payments
        Payment::factory()->count(10)->pending()->create();
        
        // Create failed payments
        Payment::factory()->count(5)->failed()->create();
    }
}