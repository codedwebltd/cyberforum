<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

trait OnboardingTrait
{
    protected function getOnboardingData()
    {
        
        $total_points = 0;
        $points_value = 0;
        $profession = null;
        $profile_basic_info = null;
        $community_preferences = null;
        $interests_skills = null;
        $privacy_settings = null;
        $onboarding_completion = 0;
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Points calculation
            $total_points = $user->points ?? 0;
            $points_value = round(($total_points * 0.03) / 10, 2); // 10 points = $0.03
            
            // Get onboarding steps
            $onboardingSteps = $user->onboardingSteps()->get()->keyBy('step_name');
            
            // Extract data from specific steps
            $profile_basic_info = $onboardingSteps->get('profile_basic_info')?->step_data ?? [];
            $community_preferences = $onboardingSteps->get('community_preferences')?->step_data ?? [];
            $interests_skills = $onboardingSteps->get('interests_skills')?->step_data ?? [];
            $privacy_settings = $onboardingSteps->get('privacy_settings')?->step_data ?? [];
            
            // Get profession from profile_basic_info
            $profession = $profile_basic_info['profession'] ?? 'Member';
            
            // Calculate completion percentage
            $totalSteps = $user->onboardingSteps()->count();
            $completedSteps = $user->onboardingSteps()->completed()->count();
            $onboarding_completion = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
        }
        
        return [
            'total_points' => $total_points,
            'points_value' => $points_value,
            'profession' => $profession,
            'profile_basic_info' => $profile_basic_info,
            'community_preferences' => $community_preferences,
            'interests_skills' => $interests_skills,
            'privacy_settings' => $privacy_settings,
            'onboarding_completion' => $onboarding_completion,
        ];
    }
}