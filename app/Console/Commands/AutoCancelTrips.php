<?php

namespace App\Console\Commands;

use App\Models\Trip;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoCancelTrips extends Command
{
    protected $signature = 'trips:auto-cancel';
    protected $description = 'Automatically cancel trips that have expired';

    public function handle()
    {
        $expiredTrips = Trip::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(30))
            ->update(['status' => 'cancelled']);

        $this->info("Expired trips have been cancelled.");
    }
}
