<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Wallet;
use App\Models\UserSetting;
use Illuminate\Support\Str;
use App\Models\UserSettings;
use App\Models\OnboardingStep;
use App\Traits\EmailNotificationTrait;
trait UserGenerationTrait
{
    use EmailNotificationTrait;

    public function generateUsername(string $email, string $firstName = null, string $lastName = null): string
    {
        $baseUsername = strtolower(explode('@', $email)[0]);

        // Add first/last name if provided
        if ($firstName && $lastName) {
            $baseUsername = strtolower($firstName . $lastName);
        }

        // Clean username
        $username = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        $username = substr($username, 0, 15);

        // Ensure uniqueness
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }

    public function generateAffiliateId(): string
    {
        do {
            $affiliateId = strtoupper(Str::random(8));
        } while (User::where('affiliate_id', $affiliateId)->exists());

        return $affiliateId;
    }

    public function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(uniqid(), -6));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function createUserRelatedModels(User $user): void
    {
        // Create wallet
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'currency' => 'USD',
        ]);

        // Create user settings
        UserSetting::create([
            'user_id' => $user->id,
        ]);

        // Create onboarding steps matching your UI
        $steps = [
            ['step_name' => 'profile_basic_info', 'step_order' => 1],
            ['step_name' => 'community_preferences', 'step_order' => 2],
            ['step_name' => 'interests_skills', 'step_order' => 3],
            ['step_name' => 'privacy_settings', 'step_order' => 4],
        ];

        foreach ($steps as $step) {
            OnboardingStep::create([
                'user_id' => $user->id,
                'step_name' => $step['step_name'],
                'step_order' => $step['step_order'],
            ]);
        }
    }

    public function processReferral(User $newUser, string $referralCode = null): void
    {
        if (!$referralCode) return;

        
        $referrer = User::where('referral_code', $referralCode)->first();
        if (!$referrer) return;
        
        // Set referral relationship
        $i = $newUser->update(['referred_by' => $referrer->id]);

        
        // Update referrer counts
        $referrer->increment('referrals_count');

        // Award points
        //$newUser->increment('points', 100); // New user bonus
        $referrer->increment('points', 200); // Referral bonus

        // Notify referrer
        $this->notifyReferralSuccess($referrer, $newUser);
    }

    private function notifyReferralSuccess(User $referrer, User $newUser): void
    {
        $points = $referrer->points;

        $message = [
            'response' => "Congratulations! {$newUser->name} just joined using your referral code. You've earned  $points!",
            'user_name' => $referrer->name,
            'notify_admin' => false,
            'subject' => 'New Referral Success',
            'type' => 'success'
        ];

        $i = $this->ActionNotification($referrer->id, $message);
        
    }
}
