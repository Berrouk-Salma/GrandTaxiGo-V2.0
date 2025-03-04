<?php

namespace App\Policies;

use App\Models\DriverAvailability;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverAvailabilityPolicy
{
    use HandlesAuthorization;

    public function update(User $user, DriverAvailability $availability)
    {
        return $user->id === $availability->driver_id;
    }

    public function delete(User $user, DriverAvailability $availability)
    {
        return $user->id === $availability->driver_id;
    }
}