<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsDriver
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isDriver()) {
            return redirect()->route('home')->with('error', 'Access denied. Driver privileges required.');
        }
        
        return $next($request);
    }
}