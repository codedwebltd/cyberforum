<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // try {
             if (Auth::check()) {
            // Update both fields
            Auth::user()->update([
                'last_active_at' => now(),
                'is_active' => true
            ]);

            Log::info('User activity tracked for user ID: ' . Auth::id());

        }

        // } catch (\Exception $e) {
        //     Log::error('Error tracking user activity: ' . $e->getMessage());
        // }
       
        return $next($request);
    }
}