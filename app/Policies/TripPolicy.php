<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isPassenger();
    }

    public function view(User $user, Trip $trip)
    {
        return $user->id === $trip->passenger_id || $user->id === $trip->driver_id;
    }

    public function cancel(User $user, Trip $trip)
    {
        return $user->id === $trip->passenger_id && $trip->status === 'pending';
    }

    public function accept(User $user, Trip $trip)
    {
        return $user->isDriver() && $trip->status === 'pending' && is_null($trip->driver_id);
    }

    public function complete(User $user, Trip $trip)
    {
        return $user->id === $trip->driver_id && $trip->status === 'accepted';
    }
}