<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Traits\LocationTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotificationMail;
use Illuminate\Http\RedirectResponse;
use App\Traits\EmailNotificationTrait;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    use LocationTrait, EmailNotificationTrait;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();

            // Update last active timestamp
            $user->updateLastActive();

            // Log login location for security tracking
            $this->logUserLocation('login');

            // Send login alert if enabled in user settings
            $this->sendLoginAlert($user);

            // Determine redirect based on user role and onboarding status
            return $this->handlePostLoginRedirect($user);

        } catch (\Exception $e) {
            // Log failed login attempt with location data
            $this->logFailedLogin($request);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Log logout location if user exists
        if ($user) {
            $this->logUserLocation('logout');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function handlePostLoginRedirect($user): RedirectResponse
    {
        // Admin users go to admin dashboard
        if ($user->is_admin) {
            return redirect()->intended('/admin/dashboard')
                ->with('success', 'Welcome back, Admin!');
        }

        // Check if onboarding is completed
        if (!$user->onboarding_completed) {
            return redirect()->route('onboarding')
                ->with('info', 'Please complete your profile setup to get started.');
        }

        // Regular users go to main dashboard
        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    private function sendLoginAlert($user): void
    {
        // Check if user has login alerts enabled
        $settings = $user->settings;

        if (!$settings || !$settings->login_alerts) {
            return;
        }

        $locationData = $this->getLocationSummary();

        $message = [
            'response' => "New login detected on your account.\n\nLocation: {$locationData['city']}, {$locationData['country']}\nTime: " . now()->format('M j, Y \a\t g:i A T') . "\n\nIf this wasn't you, please secure your account immediately.",
            'user_name' => $user->name,
            'notify_admin' => false,
            'subject' => 'Security Alert: New Login Detected',
            'type' => 'info',
            'action_url' => route('profile.security'),
            'action_text' => 'Review Security Settings'
        ];

        $this->ActionNotification($user->id, $message);
    }

   private function logFailedLogin(Request $request): void
{
    $key = 'failed_login.' . $request->ip();
    RateLimiter::hit($key, 300);

    // Log failed attempt with location data
    $locationData = $this->extractLocationData();

    // Add this line to store in user's location_history
    $this->logFailedLoginAttempt($request->email, $request->ip(), $request->userAgent(), $locationData);

    Log::warning('Failed login attempt', [
        'email' => $request->email,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'location' => $locationData,
        'timestamp' => now()
    ]);

    if (RateLimiter::attempts($key) >= 5) {
        $this->notifyAdminOfSuspiciousActivity($request, $locationData);
    }
}

  private function notifyAdminOfSuspiciousActivity(Request $request, array $locationData): void
{
    $adminEmail = config('app.admin_email', 'dakingeorge58@gmail.com');

    // Safely extract location data with better error handling
    $city = 'Unknown';
    $country = 'Unknown';

    if (is_array($locationData)) {
        if (isset($locationData['city']) && !is_array($locationData['city'])) {
            $city = (string) $locationData['city'];
        }
        
        if (isset($locationData['country']) && !is_array($locationData['country'])) {
            $country = (string) $locationData['country'];
        }
    }

    // Get attempts count safely
    $attempts = 0;
    try {
        $rateLimitKey = 'failed_login.' . $request->ip();
        $attempts = (int) RateLimiter::attempts($rateLimitKey);
    } catch (\Exception $e) {
        Log::warning('Could not retrieve rate limit attempts', ['error' => $e->getMessage()]);
    }

    // Ensure all string values are properly handled
    $email = $request->input('email', 'Unknown');
    $ip = $request->ip() ?? 'Unknown';

    $message = [
        'response' => "Multiple failed login attempts detected.\n\n" .
                      "Email: {$email}\n" .
                      "IP: {$ip}\n" .
                      "Location: {$city}, {$country}\n" .
                      "Attempts: {$attempts}\n\n" .
                      "Please review security logs.",
        'user_name' => 'Administrator',
        'notify_admin' => true,
        'subject' => 'Security Alert: Multiple Failed Login Attempts',
        'type' => 'error'
    ];

    try {
        Mail::to($adminEmail)->send(new GeneralNotificationMail($message));
        
        Log::info('Admin security alert sent successfully', [
            'email' => $email,
            'ip' => $ip,
            'attempts' => $attempts
        ]);
        
    } catch (\Exception $e) {
        Log::error('Failed to send admin security alert', [
            'error' => $e->getMessage(),
            'email' => $email,
            'ip' => $ip,
            'location_data' => is_array($locationData) ? json_encode($locationData) : $locationData
        ]);
    }
}
}
