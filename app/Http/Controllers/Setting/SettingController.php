<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display the settings page with current user settings
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create user settings
        $settings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => true,
                'push_notifications' => true,
                'comment_notifications' => true,
                'like_notifications' => true,
                'follow_notifications' => true,
                'mention_notifications' => true,
                'reply_notifications' => true,
                'weekly_digest' => false,
                'marketing_emails' => false,
                'profile_visibility' => 'public',
                'show_online_status' => true,
                'allow_friend_requests' => true,
                'allow_messages_from_strangers' => false,
                'content_preferences' => json_encode(['interests' => []]),
                'content_language' => 'en',
                'timezone' => 'UTC',
                'theme' => 'auto',
                'two_factor_enabled' => false,
                'login_alerts' => true,
            ]
        );

        return view('home.settings.index', compact('settings', 'user'));
    }

    /**
     * Update user settings
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            // Notification settings
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'comment_notifications' => 'boolean',
            'like_notifications' => 'boolean',
            'follow_notifications' => 'boolean',
            'mention_notifications' => 'boolean',
            'reply_notifications' => 'boolean',
            'weekly_digest' => 'boolean',
            'marketing_emails' => 'boolean',
            
            // Privacy settings
            'profile_visibility' => ['required', Rule::in(['public', 'followers', 'private'])],
            'show_online_status' => 'boolean',
            'allow_friend_requests' => 'boolean',
            'allow_messages_from_strangers' => 'boolean',
            
            // Preferences
            'content_language' => ['required', Rule::in(['en', 'es', 'fr', 'de', 'pt'])],
            'timezone' => ['required', Rule::in(['UTC', 'EST', 'PST', 'GMT', 'CET'])],
            'theme' => ['required', Rule::in(['light', 'dark', 'auto'])],
            
            // Security
            'two_factor_enabled' => 'boolean',
            'login_alerts' => 'boolean',
            
            // Content preferences
            'interests' => 'array',
            'interests.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please correct the errors below.');
        }

        try {
            // Get or create user settings
            $settings = UserSetting::firstOrCreate(['user_id' => $user->id]);

            // Prepare content preferences
            $contentPreferences = [
                'interests' => $request->input('interests', []),
                'updated_at' => now()->toISOString()
            ];

            // Update settings
            $settings->update([
                // Notification settings
                'email_notifications' => $request->boolean('email_notifications'),
                'push_notifications' => $request->boolean('push_notifications'),
                'comment_notifications' => $request->boolean('comment_notifications'),
                'like_notifications' => $request->boolean('like_notifications'),
                'follow_notifications' => $request->boolean('follow_notifications'),
                'mention_notifications' => $request->boolean('mention_notifications'),
                'reply_notifications' => $request->boolean('reply_notifications'),
                'weekly_digest' => $request->boolean('weekly_digest'),
                'marketing_emails' => $request->boolean('marketing_emails'),
                
                // Privacy settings
                'profile_visibility' => $request->input('profile_visibility'),
                'show_online_status' => $request->boolean('show_online_status'),
                'allow_friend_requests' => $request->boolean('allow_friend_requests'),
                'allow_messages_from_strangers' => $request->boolean('allow_messages_from_strangers'),
                
                // Preferences
                'content_preferences' => json_encode($contentPreferences),
                'content_language' => $request->input('content_language'),
                'timezone' => $request->input('timezone'),
                'theme' => $request->input('theme'),
                
                // Security settings
                'two_factor_enabled' => $request->boolean('two_factor_enabled'),
                'login_alerts' => $request->boolean('login_alerts'),
            ]);

            // If 2FA is being enabled, redirect to 2FA setup
            if ($request->boolean('two_factor_enabled') && !$settings->getOriginal('two_factor_enabled')) {
                return redirect()->route('settings')
                    ->with('success', 'Settings updated successfully!')
                    ->with('info', 'Please complete your two-factor authentication setup.');
            }

            return redirect()->route('settings')
                ->with('success', 'Your settings have been updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating your settings. Please try again.');
        }
    }

    /**
     * Reset settings to default values
     */
    public function reset(Request $request)
    {
        $user = Auth::user();
        
        try {
            UserSetting::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'email_notifications' => true,
                    'push_notifications' => true,
                    'comment_notifications' => true,
                    'like_notifications' => true,
                    'follow_notifications' => true,
                    'mention_notifications' => true,
                    'reply_notifications' => true,
                    'weekly_digest' => false,
                    'marketing_emails' => false,
                    'profile_visibility' => 'public',
                    'show_online_status' => true,
                    'allow_friend_requests' => true,
                    'allow_messages_from_strangers' => false,
                    'content_preferences' => json_encode(['interests' => []]),
                    'content_language' => 'en',
                    'timezone' => 'UTC',
                    'theme' => 'auto',
                    'two_factor_enabled' => false,
                    'login_alerts' => true,
                ]
            );

            return redirect()->route('settings')
                ->with('success', 'Settings have been reset to default values.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while resetting your settings.');
        }
    }

    /**
     * Export user data
     */
    public function exportData(Request $request)
    {
        $user = Auth::user();
        $settings = UserSetting::where('user_id', $user->id)->first();

        $userData = [
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'email_verified_at' => $user->email_verified_at,
            ],
            'settings' => $settings ? $settings->toArray() : [],
            'exported_at' => now()->toISOString(),
        ];

        $fileName = 'user_data_' . $user->id . '_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($userData)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Deactivate user account
     */
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DELETE',
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();

        try {
            // Soft delete or deactivate the user account
            // You might want to implement a soft delete or deactivation flag
            $user->update([
                'email_verified_at' => null,
                'deactivated_at' => now(),
            ]);

            // Delete user settings
            UserSetting::where('user_id', $user->id)->delete();

            Auth::logout();

            return redirect()->route('login')
                ->with('success', 'Your account has been deactivated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deactivating your account.');
        }
    }

    /**
     * Get user settings for API endpoints
     */
    public function getSettings()
    {
        $user = Auth::user();
        $settings = UserSetting::where('user_id', $user->id)->first();

        if (!$settings) {
            return response()->json([
                'message' => 'No settings found for this user'
            ], 404);
        }

        return response()->json([
            'settings' => $settings,
            'user' => $user->only(['name', 'email', 'created_at'])
        ]);
    }
}
