<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\WelcomeUserMail;
use App\Traits\LocationTrait;
use Illuminate\Validation\Rules;
use App\Traits\UserGenerationTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Traits\EmailNotificationTrait;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    use UserGenerationTrait, EmailNotificationTrait, LocationTrait;

    public function create(): View
    {
        //dd(User::delete());
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
            'terms' => ['accepted'],
        ]);

        
        try {
            // Generate user data
            $username = $this->generateUsername(
                $request->email,
                $request->first_name,
                $request->last_name
            );

            $affiliateId = $this->generateAffiliateId();
            $referralCode = $this->generateReferralCode();

            // Extract location data
            $locationData = $this->extractLocationData();

            //Create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_confirmation' => $request->password,
                'username' => $username,
                'affiliate_id' => $affiliateId,
                'referral_code' => $referralCode,
                'location_history' => json_encode([$locationData]),
                'points' => 10, // Welcome bonus
                'capped_file_size' => 500, // Default 1GB in MB
            ]);

            //Create related models
            $this->createUserRelatedModels($user);

             //Send welcome email
            $this->sendWelcomeEmail($user);

            //Process referral if provided
            if ($request->referral_code) {    
                $this->processReferral($user, $request->referral_code);
            }

            //Send email verification
            //$this->sendEmailVerification($user);

            //Fire registered event
            event(new Registered($user));

            // Log user in
            Auth::login($user);

            // Log registration action
            $this->logUserLocation('registration');

            return redirect()->route('home')
                ->with('success', 'Registration successful! Welcome to our community.');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    public function sendWelcomeEmail($user): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user));
        } catch (\Exception $e) {
            Log::error('Welcome email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }
    }
}
