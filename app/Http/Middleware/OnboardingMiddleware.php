<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and onboarding is incomplete
        if (auth()->check() && !auth()->user()->onboarding_completed) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}