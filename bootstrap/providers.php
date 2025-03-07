<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Add a directive for star ratings
        Blade::directive('stars', function ($rating) {
            return "<?php echo \App\Helpers\BladeHelpers::renderStars($rating); ?>";
        });

        // Add a directive for user role checks
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->role == $role): ?>";
        });
        
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // Add a directive for user type checks
        Blade::directive('usertype', function ($type) {
            return "<?php if(auth()->check() && auth()->user()->user_type == $type): ?>";
        });
        
        Blade::directive('endusertype', function () {
            return "<?php endif; ?>";
        });
    }
}