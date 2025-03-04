<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Unable to login using ' . $provider . '. Please try again.');
        }

        // Check if user exists with same email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create a new user account
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'user_type' => 'passenger', // Default to passenger for social logins
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'profile_image' => $socialUser->getAvatar(),
                'email_verified_at' => now(), // Assume social login means email is verified
            ]);
        } else {
            // Update provider details if they don't exist
            if (!$user->provider) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            }
        }

        // Login the user
        Auth::login($user, true);

        // Redirect to home page or ask for additional info if first login
        if ($user->phone === null) {
            return redirect()->route('profile.edit')
                ->with('info', 'Please complete your profile by adding your phone number and profile image.');
        }

        return redirect()->intended('home');
    }
}