<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
