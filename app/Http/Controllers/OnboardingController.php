<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OnboardingStep;
use App\Services\FileUploadService;
use App\Traits\LocationTrait;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    use LocationTrait;

   public function onboarding()
{
    $user = auth()->user();
    
    // Check if onboarding is already completed
    if ($user->onboarding_completed) {
        Log::info('User tried to access onboarding but already completed', [
            'user_id' => $user->id,
            'completion_status' => true
        ]);
        
        return redirect()->route('home')->with('info', 'You have already completed the onboarding process.');
    }
    
    $completedSteps = $user->onboardingSteps()
        ->where('is_completed', true)
        ->pluck('step_name');

    Log::info('Onboarding page accessed', [
        'user_id' => $user->id,
        'completed_steps' => $completedSteps->toArray(),
        'onboarding_completed' => $user->onboarding_completed
    ]);

    return view('home.onboarding', compact('completedSteps'));
}

    public function updateStep(Request $request)
{
    $user = auth()->user();
    $stepName = $request->step_name;

    // Comprehensive logging
    Log::info('=== ONBOARDING STEP SUBMISSION ===', [
        'user_id' => $user->id,
        'step_name' => $stepName,
        'request_method' => $request->method(),
        'user_agent' => $request->userAgent(),
        'ip_address' => $request->ip(),
        'all_input' => $request->all(),
        'has_files' => !empty($request->allFiles()),
        'files_info' => collect($request->allFiles())->map(function($file) {
            return [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'error' => $file->getError()
            ];
        }),
        'headers' => [
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'x_requested_with' => $request->header('X-Requested-With')
        ]
    ]);

    try {
        $stepData = match($stepName) {
            'profile_basic_info' => $this->handleProfileBasicInfo($request, $user),
            'community_preferences' => $this->handleCommunityPreferences($request, $user),
            'interests_skills' => $this->handleInterestsSkills($request, $user),
            'privacy_settings' => $this->handlePrivacySettings($request, $user),
            default => []
        };

        Log::info('Step data processed successfully', [
            'step_name' => $stepName,
            'step_data' => $stepData
        ]);

        // Mark step as completed
        OnboardingStep::updateOrCreate(
            ['user_id' => $user->id, 'step_name' => $stepName],
            [
                'is_completed' => true,
                'completed_at' => now(),
                'step_data' => $stepData,
                'attempts' => 1,
                'last_attempt_at' => now()
            ]
        );

        // Check if all steps completed
        $totalSteps = 4;
        $completedSteps = OnboardingStep::where('user_id', $user->id)
            ->where('is_completed', true)->count();

        Log::info('Onboarding progress check', [
            'user_id' => $user->id,
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps,
            'current_step' => $stepName
        ]);

        if ($completedSteps >= $totalSteps) {
            $this->completeOnboarding($user);
            $this->logUserLocation('onboarding_completed');
            
            return redirect()->route('home')->with('success', 'Welcome to CyberForum! Your profile is now complete.');
        }

        Log::info('Redirecting to next onboarding step', [
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps
        ]);

        return redirect()->route('onboarding')->with('success', 'Step completed successfully!');

    } catch (\Exception $e) {
        Log::error('Onboarding step failed', [
            'user_id' => $user->id,
            'step_name' => $stepName,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    }
}

    private function handleProfileBasicInfo(Request $request, User $user): array
{
    Log::info('=== HANDLING PROFILE BASIC INFO ===', [
        'has_avatar_file' => $request->hasFile('avatar'),
        'has_base64_avatar' => $request->has('avatar_base64'),
        'is_mobile_upload' => $request->has('is_mobile_upload'),
        'avatar_file_info' => $request->hasFile('avatar') ? [
            'name' => $request->file('avatar')->getClientOriginalName(),
            'size' => $request->file('avatar')->getSize(),
            'type' => $request->file('avatar')->getMimeType(),
        ] : null,
        'base64_info' => $request->has('avatar_base64') ? [
            'name' => $request->avatar_name,
            'type' => $request->avatar_type,
            'size' => $request->avatar_size,
            'base64_length' => strlen($request->avatar_base64)
        ] : null
    ]);

    $request->validate([
        'date_of_birth' => 'nullable|date|before:today',
        'gender' => 'nullable|in:male,female,non-binary,prefer-not-to-say',
        'location' => 'nullable|string|max:255',
        'profession' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:150',
        'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        // Mobile upload fields
        'avatar_base64' => 'nullable|string',
        'avatar_name' => 'nullable|string',
        'avatar_type' => 'nullable|string',
        'avatar_size' => 'nullable|integer',
        'is_mobile_upload' => 'nullable|string'
    ]);

    $avatarUrl = null;
    
   // Handle file upload (desktop or mobile)
    if ($request->hasFile('avatar')) {
        // Regular file upload (desktop)
        Log::info('Processing regular file upload');
        $fileService = app(FileUploadService::class);
        $result = $fileService->replaceFile(
            $request->file('avatar'),
            'social/avatars',
            $user->avatar_url,  // ← Current avatar URL to replace
            $user->id
        );
        $avatarUrl = $result['url'];
        
    } elseif ($request->has('avatar_base64') && $request->is_mobile_upload) {
        // Base64 upload (mobile)
        Log::info('Processing base64 mobile upload');
        
        try {
            // Convert base64 to file
            $base64Data = $request->avatar_base64;
            $fileName = $request->avatar_name;
            $fileType = $request->avatar_type;
            
            // Remove data:image/jpeg;base64, prefix
            if (strpos($base64Data, ',') !== false) {
                $base64Data = explode(',', $base64Data)[1];
            }
            
            $fileData = base64_decode($base64Data);
            
            if ($fileData === false) {
                throw new \Exception('Failed to decode base64 data');
            }
            
            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'mobile_upload_');
            file_put_contents($tempFile, $fileData);
            
            // Create UploadedFile object
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFile,
                $fileName,
                $fileType,
                null,
                true
            );
            
            Log::info('Created temporary file', [
                'temp_path' => $tempFile,
                'file_size' => filesize($tempFile)
            ]);
            
            // Upload using your existing service
            $fileService = app(FileUploadService::class);
            $result = $fileService->replaceFile(
                $uploadedFile,
                'social/avatars',
                $user->avatar_url,  // ← Current avatar URL to replace
                $user->id
            );
            
            $avatarUrl = $result['url'];
            
            // Clean up temp file
            unlink($tempFile);
            
            Log::info('Mobile upload successful', ['url' => $avatarUrl]);
            
        } catch (\Exception $e) {
            Log::error('Mobile upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // Update user profile using dedicated method
    $this->updateUserProfile($user, [
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'location' => $request->location,
        'bio' => $request->bio,
        'avatar_url' => $avatarUrl
    ]);

    // Update profile completion
    $this->updateProfileCompletion($user);

    return [
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'location' => $request->location,
        'profession' => $request->profession,
        'bio' => $request->bio,
        'avatar_uploaded' => !is_null($avatarUrl),
        'avatar_url' => $avatarUrl,
        'upload_method' => $request->is_mobile_upload ? 'mobile_base64' : 'regular_file',
        'completed_fields' => count(array_filter([
            $request->date_of_birth,
            $request->gender,
            $request->location,
            $request->profession,
            $request->bio,
            $avatarUrl
        ]))
    ];
}

    private function handleCommunityPreferences(Request $request, User $user): array
{
    Log::info('=== HANDLING COMMUNITY PREFERENCES ===', [
        'interests' => $request->interests,
        'engagement_level' => $request->engagement_level
    ]);

    $request->validate([
        'interests' => 'array|max:5',
        'interests.*' => 'string|in:technology,gaming,design,business,science,art-creative,sports,health-fitness,entertainment',
        'engagement_level' => 'required|in:lurker,occasional,active'
    ]);

    // Update community preferences using dedicated method
    $this->updateCommunityPreferences($user, $request);
    
    // Update profile completion
    $this->updateProfileCompletion($user);

    return [
        'interests' => $request->interests ?? [],
        'engagement_level' => $request->engagement_level,
        'interest_count' => count($request->interests ?? [])
    ];
}

    private function handleInterestsSkills(Request $request, User $user): array
{
    Log::info('=== HANDLING INTERESTS & SKILLS ===', [
        'skills' => $request->skills,
        'experience_level' => $request->experience_level,
        'social_links' => $request->social_links
    ]);

    $request->validate([
        'skills' => 'array',
        'skills.*' => 'string|max:50',
        'experience_level' => 'required|in:beginner,intermediate,expert',
        'social_links' => 'array',
        'social_links.twitter' => 'nullable|url',
        'social_links.linkedin' => 'nullable|url',
        'social_links.github' => 'nullable|url'
    ]);

    // Update profile completion
    $this->updateProfileCompletion($user);

    return [
        'skills' => $request->skills ?? [],
        'experience_level' => $request->experience_level,
        'social_links' => array_filter($request->social_links ?? []),
        'skills_count' => count($request->skills ?? [])
    ];
}

   private function handlePrivacySettings(Request $request, User $user): array
{
    Log::info('=== HANDLING PRIVACY SETTINGS ===', [
        'all_input' => $request->all(),
        'privacy_checkboxes' => [
            'profile_public' => $request->has('profile_public'),
            'show_online_status' => $request->has('show_online_status'),
            'allow_messages' => $request->has('allow_messages'),
        ],
        'notification_checkboxes' => [
            'email_notifications' => $request->has('email_notifications'),
            'reply_notifications' => $request->has('reply_notifications'),
            'weekly_digest' => $request->has('weekly_digest'),
            'marketing_emails' => $request->has('marketing_emails'),
        ]
    ]);

    $request->validate([
        'profile_public' => 'boolean',
        'show_online_status' => 'boolean',
        'allow_messages' => 'boolean',
        'email_notifications' => 'boolean',
        'reply_notifications' => 'boolean',
        'weekly_digest' => 'boolean',
        'marketing_emails' => 'boolean'
    ]);

    // Update privacy settings using dedicated methods
    $this->updateUserPrivacy($user, $request);
    $this->updateUserNotifications($user, $request);
    
    // Update profile completion
    $this->updateProfileCompletion($user);

    $privacyData = [
        'profile_public' => $request->boolean('profile_public', false),
        'show_online_status' => $request->boolean('show_online_status', false),
        'allow_messages' => $request->boolean('allow_messages', false),
        'email_notifications' => $request->boolean('email_notifications', false),
        'reply_notifications' => $request->boolean('reply_notifications', false),
        'weekly_digest' => $request->boolean('weekly_digest', false),
        'marketing_emails' => $request->boolean('marketing_emails', false),
        'privacy_level' => $request->boolean('profile_public') ? 'public' : 'private',
        'notification_count' => collect([
            $request->boolean('email_notifications'),
            $request->boolean('reply_notifications'),
            $request->boolean('weekly_digest'),
            $request->boolean('marketing_emails')
        ])->filter()->count()
    ];

    Log::info('Privacy settings processed', ['privacy_data' => $privacyData]);

    return $privacyData;
}

    public function skip(Request $request)
{
    $user = auth()->user();
    //$user->update(['onboarding_completed' => true]);

    $this->logUserLocation('onboarding_skipped');

    return redirect()->route('dashboard')->with('success', 'Welcome to CyberForum!');
}


// Helper to log user tabl data
/**
 * Update user profile fields during onboarding
 */
private function updateUserProfile(User $user, array $data): void
{
    $filteredData = array_filter($data, function($value) {
        return !is_null($value) && $value !== '';
    });

    if (!empty($filteredData)) {
        $user->update($filteredData);
        
        Log::info('User profile updated', [
            'user_id' => $user->id,
            'updated_fields' => array_keys($filteredData),
            'data' => $filteredData
        ]);
    }
}

/**
 * Update user privacy settings
 */
private function updateUserPrivacy(User $user, Request $request): void
{
    $privacyData = [
        'profile_public' => $request->boolean('profile_public', false),
        'show_online_status' => $request->boolean('show_online_status', false),
        'allow_messages' => $request->boolean('allow_messages', false)
    ];

    $user->update($privacyData);

    Log::info('User privacy settings updated', [
        'user_id' => $user->id,
        'privacy_data' => $privacyData
    ]);
}

/**
 * Update user notification settings
 */
private function updateUserNotifications(User $user, Request $request): void
{
    // Ensure user has settings record
    if (!$user->settings) {
        $user->settings()->create([]);
    }

    $notificationData = [
        'email_notifications' => $request->boolean('email_notifications', false),
        'reply_notifications' => $request->boolean('reply_notifications', false),
        'weekly_digest' => $request->boolean('weekly_digest', false),
        'marketing_emails' => $request->boolean('marketing_emails', false)
    ];

    $user->settings->update($notificationData);

    Log::info('User notification settings updated', [
        'user_id' => $user->id,
        'notification_data' => $notificationData
    ]);
}

/**
 * Update user community preferences
 */
private function updateCommunityPreferences(User $user, Request $request): void
{
    // Ensure user has settings record
    if (!$user->settings) {
        $user->settings()->create([]);
    }

    $preferences = [
        'content_preferences' => json_encode([
            'interests' => $request->interests ?? [],
            'engagement_level' => $request->engagement_level
        ])
    ];

    $user->settings->update($preferences);

    Log::info('Community preferences updated', [
        'user_id' => $user->id,
        'preferences' => $preferences
    ]);
}

/**
 * Calculate and update profile completion percentage
 */
private function updateProfileCompletion(User $user): int
{
    $percentage = $user->calculateProfileCompletion();
    
    Log::info('Profile completion updated', [
        'user_id' => $user->id,
        'completion_percentage' => $percentage
    ]);

    return $percentage;
}

/**
 * Mark onboarding as completed
 */
private function completeOnboarding(User $user): void
{
    $user->update([
        'onboarding_completed' => true,
        'last_active_at' => now()
    ]);

    // Final profile completion calculation
    $this->updateProfileCompletion($user);

    Log::info('Onboarding marked as completed', [
        'user_id' => $user->id,
        'completed_at' => now(),
        'final_completion_percentage' => $user->profile_completion_percentage
    ]);
}
}