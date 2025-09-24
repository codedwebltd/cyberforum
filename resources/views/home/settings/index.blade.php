@extends('inc.home.app')
@section('title', 'Settings - ' . ($settings->site_name ?? config('app.name')))
@section('content')

<!-- Main Content -->
<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
            <!-- Main Settings Area -->
            <div class="space-y-6 xl:col-span-3">
                @include('session-message.session-message')
                
                <!-- Page Header -->
                <div class="p-6 forum-card rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-primary">
                            <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold font-display">Account Settings</h1>
                            <p class="text-sm text-muted-foreground">Manage your account preferences and privacy settings</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Notification Settings -->
                    <div class="p-6 forum-card rounded-xl">
                        <div class="flex items-center mb-6 space-x-2">
                            <i data-lucide="bell" class="w-5 h-5 text-primary"></i>
                            <h2 class="text-xl font-semibold font-display">Notification Preferences</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Email Notifications -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Email Notifications</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Email Notifications</label>
                                            <p class="text-xs text-muted-foreground">Receive notifications via email</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="email_notifications" id="email_notifications" 
                                                   class="sr-only" {{ old('email_notifications', $settings->email_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="email_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Weekly Digest</label>
                                            <p class="text-xs text-muted-foreground">Weekly summary of community activity</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="weekly_digest" id="weekly_digest" 
                                                   class="sr-only" {{ old('weekly_digest', $settings->weekly_digest ?? false) ? 'checked' : '' }}>
                                            <label for="weekly_digest" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Marketing Emails</label>
                                            <p class="text-xs text-muted-foreground">Promotional content and updates</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="marketing_emails" id="marketing_emails" 
                                                   class="sr-only" {{ old('marketing_emails', $settings->marketing_emails ?? false) ? 'checked' : '' }}>
                                            <label for="marketing_emails" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Push Notifications -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Push Notifications</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Push Notifications</label>
                                            <p class="text-xs text-muted-foreground">Browser push notifications</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="push_notifications" id="push_notifications" 
                                                   class="sr-only" {{ old('push_notifications', $settings->push_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="push_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Comments</label>
                                            <p class="text-xs text-muted-foreground">When someone comments on your posts</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="comment_notifications" id="comment_notifications" 
                                                   class="sr-only" {{ old('comment_notifications', $settings->comment_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="comment_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Likes & Reactions</label>
                                            <p class="text-xs text-muted-foreground">When someone likes your content</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="like_notifications" id="like_notifications" 
                                                   class="sr-only" {{ old('like_notifications', $settings->like_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="like_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Follows</label>
                                            <p class="text-xs text-muted-foreground">When someone follows you</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="follow_notifications" id="follow_notifications" 
                                                   class="sr-only" {{ old('follow_notifications', $settings->follow_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="follow_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Mentions</label>
                                            <p class="text-xs text-muted-foreground">When someone mentions you</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="mention_notifications" id="mention_notifications" 
                                                   class="sr-only" {{ old('mention_notifications', $settings->mention_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="mention_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Replies</label>
                                            <p class="text-xs text-muted-foreground">When someone replies to your comments</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="reply_notifications" id="reply_notifications" 
                                                   class="sr-only" {{ old('reply_notifications', $settings->reply_notifications ?? true) ? 'checked' : '' }}>
                                            <label for="reply_notifications" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="p-6 forum-card rounded-xl">
                        <div class="flex items-center mb-6 space-x-2">
                            <i data-lucide="shield" class="w-5 h-5 text-accent"></i>
                            <h2 class="text-xl font-semibold font-display">Privacy & Security</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Profile Privacy -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Profile Privacy</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Profile Visibility</label>
                                        <select name="profile_visibility" class="w-full p-3 border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            <option value="public" {{ old('profile_visibility', $settings->profile_visibility ?? 'public') == 'public' ? 'selected' : '' }}>Public - Anyone can view</option>
                                            <option value="followers" {{ old('profile_visibility', $settings->profile_visibility ?? 'public') == 'followers' ? 'selected' : '' }}>Followers Only</option>
                                            <option value="private" {{ old('profile_visibility', $settings->profile_visibility ?? 'public') == 'private' ? 'selected' : '' }}>Private - Invitation only</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Show Online Status</label>
                                            <p class="text-xs text-muted-foreground">Let others see when you're online</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="show_online_status" id="show_online_status" 
                                                   class="sr-only" {{ old('show_online_status', $settings->show_online_status ?? true) ? 'checked' : '' }}>
                                            <label for="show_online_status" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Allow Friend Requests</label>
                                            <p class="text-xs text-muted-foreground">Let others send you friend requests</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="allow_friend_requests" id="allow_friend_requests" 
                                                   class="sr-only" {{ old('allow_friend_requests', $settings->allow_friend_requests ?? true) ? 'checked' : '' }}>
                                            <label for="allow_friend_requests" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Messages from Strangers</label>
                                            <p class="text-xs text-muted-foreground">Allow messages from non-connections</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="allow_messages_from_strangers" id="allow_messages_from_strangers" 
                                                   class="sr-only" {{ old('allow_messages_from_strangers', $settings->allow_messages_from_strangers ?? false) ? 'checked' : '' }}>
                                            <label for="allow_messages_from_strangers" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Settings -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Security</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Two-Factor Authentication</label>
                                            <p class="text-xs text-muted-foreground">Add an extra layer of security</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" 
                                                   class="sr-only" {{ old('two_factor_enabled', $settings->two_factor_enabled ?? false) ? 'checked' : '' }}>
                                            <label for="two_factor_enabled" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between p-3 border rounded-lg border-border">
                                        <div>
                                            <label class="text-sm font-medium">Login Alerts</label>
                                            <p class="text-xs text-muted-foreground">Get notified of new login attempts</p>
                                        </div>
                                        <div class="relative">
                                            <input type="checkbox" name="login_alerts" id="login_alerts" 
                                                   class="sr-only" {{ old('login_alerts', $settings->login_alerts ?? true) ? 'checked' : '' }}>
                                            <label for="login_alerts" class="toggle-switch">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    @php
$locationHistory = $user->location_history ?? [];
$recentFailed = collect($locationHistory)
    ->where('action', 'failed_login')
    ->filter(function($item) {
        return isset($item['timestamp']) && 
               \Carbon\Carbon::parse($item['timestamp'])->isAfter(now()->subDays(7));
    })
    ->count();
@endphp

<div class="p-4 border rounded-lg {{ $recentFailed > 5 ? 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800' : ($recentFailed > 2 ? 'bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:border-orange-800' : 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800') }}">
    <div class="flex items-center space-x-2 mb-2">
        <i data-lucide="{{ $recentFailed > 5 ? 'shield-x' : ($recentFailed > 2 ? 'shield-alert' : 'shield-check') }}" class="w-4 h-4 {{ $recentFailed > 5 ? 'text-red-600' : ($recentFailed > 2 ? 'text-orange-600' : 'text-green-600') }}"></i>
        <span class="text-sm font-medium {{ $recentFailed > 5 ? 'text-red-600' : ($recentFailed > 2 ? 'text-orange-600' : 'text-green-600') }}">Security Status</span>
    </div>
    <p class="text-xs text-gray-600 dark:text-gray-400">
        Your account security score: <span class="font-medium {{ $recentFailed > 5 ? 'text-red-600' : ($recentFailed > 2 ? 'text-orange-600' : 'text-green-600') }}">
            {{ $recentFailed > 5 ? 'At Risk' : ($recentFailed > 2 ? 'Warning' : 'Good') }}
        </span>
    </p>
    <a href="{{ route('security.index') }}" class="mt-2 text-xs font-medium {{ $recentFailed > 5 ? 'text-red-600 hover:text-red-700' : ($recentFailed > 2 ? 'text-orange-600 hover:text-orange-700' : 'text-green-600 hover:text-green-700') }} hover:underline">View Security Report</a>
</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="p-6 forum-card rounded-xl">
                        <div class="flex items-center mb-6 space-x-2">
                            <i data-lucide="palette" class="w-5 h-5 text-neon-pink"></i>
                            <h2 class="text-xl font-semibold font-display">Preferences</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Language & Region -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Language & Region</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Content Language</label>
                                        <select name="content_language" class="w-full p-3 border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            <option value="en" {{ old('content_language', $settings->content_language ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="es" {{ old('content_language', $settings->content_language ?? 'en') == 'es' ? 'selected' : '' }}>Español</option>
                                            <option value="fr" {{ old('content_language', $settings->content_language ?? 'en') == 'fr' ? 'selected' : '' }}>Français</option>
                                            <option value="de" {{ old('content_language', $settings->content_language ?? 'en') == 'de' ? 'selected' : '' }}>Deutsch</option>
                                            <option value="pt" {{ old('content_language', $settings->content_language ?? 'en') == 'pt' ? 'selected' : '' }}>Português</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Timezone</label>
                                        <select name="timezone" class="w-full p-3 border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50">
                                            <option value="UTC" {{ old('timezone', $settings->timezone ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                                            <option value="EST" {{ old('timezone', $settings->timezone ?? 'UTC') == 'EST' ? 'selected' : '' }}>EST (Eastern Standard Time)</option>
                                            <option value="PST" {{ old('timezone', $settings->timezone ?? 'UTC') == 'PST' ? 'selected' : '' }}>PST (Pacific Standard Time)</option>
                                            <option value="GMT" {{ old('timezone', $settings->timezone ?? 'UTC') == 'GMT' ? 'selected' : '' }}>GMT (Greenwich Mean Time)</option>
                                            <option value="CET" {{ old('timezone', $settings->timezone ?? 'UTC') == 'CET' ? 'selected' : '' }}>CET (Central European Time)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Appearance</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Theme</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer border-border hover:bg-muted">
                                                <input type="radio" name="theme" value="light" class="mr-3 text-primary" 
                                                       {{ old('theme', $settings->theme ?? 'auto') == 'light' ? 'checked' : '' }}>
                                                <div class="flex items-center space-x-2">
                                                    <i data-lucide="sun" class="w-4 h-4"></i>
                                                    <span class="text-sm">Light</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer border-border hover:bg-muted">
                                                <input type="radio" name="theme" value="dark" class="mr-3 text-primary"
                                                       {{ old('theme', $settings->theme ?? 'auto') == 'dark' ? 'checked' : '' }}>
                                                <div class="flex items-center space-x-2">
                                                    <i data-lucide="moon" class="w-4 h-4"></i>
                                                    <span class="text-sm">Dark</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer border-border hover:bg-muted">
                                                <input type="radio" name="theme" value="auto" class="mr-3 text-primary"
                                                       {{ old('theme', $settings->theme ?? 'auto') == 'auto' ? 'checked' : '' }}>
                                                <div class="flex items-center space-x-2">
                                                    <i data-lucide="monitor" class="w-4 h-4"></i>
                                                    <span class="text-sm">Auto (System)</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Preferences -->
                            <div class="space-y-4">
                                <h3 class="font-medium text-foreground">Content Interests</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium">Your Interests</label>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $interests = ['Web Development', 'Mobile Apps', 'AI/ML', 'Design', 'DevOps', 'Data Science', 'Gaming', 'Startup'];
                                                $userInterests = old('interests', json_decode($settings->content_preferences ?? '{}', true)['interests'] ?? []);
                                            @endphp
                                            @foreach($interests as $interest)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="interests[]" value="{{ $interest }}" 
                                                       class="sr-only" {{ in_array($interest, $userInterests) ? 'checked' : '' }}>
                                                <span class="px-3 py-1 text-xs border rounded-full cursor-pointer interest-tag border-border hover:border-primary transition-colors {{ in_array($interest, $userInterests) ? 'bg-primary text-primary-foreground' : 'bg-muted' }}">
                                                    {{ $interest }}
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="p-6 forum-card rounded-xl">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="font-medium text-foreground">Save Changes</h3>
                                <p class="text-sm text-muted-foreground">Your preferences will be applied immediately</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" class="px-6 py-2 transition-colors border rounded-lg border-border hover:bg-muted">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 font-medium transition-colors rounded-lg bg-primary text-primary-foreground hover:bg-primary/90">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6 xl:col-span-1">
                <!-- Account Overview -->
                <div class="p-5 forum-card rounded-xl">
                    <div class="flex items-center mb-4 space-x-2">
                        <i data-lucide="user-check" class="w-5 h-5 text-primary"></i>
                        <h3 class="font-semibold font-display">Account Overview</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Member Since</span>
                            <span class="text-sm font-medium">{{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Account Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-neon-green text-white">Active</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Email Verified</span>
                            @if(auth()->user()->email_verified_at)
                                <i data-lucide="check-circle" class="w-4 h-4 text-neon-green"></i>
                            @else
                                <i data-lucide="x-circle" class="w-4 h-4 text-red-500"></i>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="p-5 forum-card rounded-xl">
                    <div class="flex items-center mb-4 space-x-2">
                        <i data-lucide="zap" class="w-5 h-5 text-accent"></i>
                        <h3 class="font-semibold font-display">Quick Actions</h3>
                    </div>
                    <div class="space-y-2">
                        <button class="w-full p-3 text-left transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="download" class="w-4 h-4 text-muted-foreground"></i>
                                <span class="text-sm">Download My Data</span>
                            </div>
                        </button>
                        <button class="w-full p-3 text-left transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="key" class="w-4 h-4 text-muted-foreground"></i>
                                <span class="text-sm">Change Password</span>
                            </div>
                        </button>
                        <button class="w-full p-3 text-left transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="user-x" class="w-4 h-4 text-red-500"></i>
                                <span class="text-sm text-red-500">Deactivate Account</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="p-5 forum-card rounded-xl">
                    <div class="flex items-center mb-4 space-x-2">
                        <i data-lucide="help-circle" class="w-5 h-5 text-neon-pink"></i>
                        <h3 class="font-semibold font-display">Help & Support</h3>
                    </div>
                    <div class="space-y-2">
                        <a href="#" class="block p-3 transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="book-open" class="w-4 h-4 text-muted-foreground"></i>
                                <span class="text-sm">Documentation</span>
                            </div>
                        </a>
                        <a href="#" class="block p-3 transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="message-circle" class="w-4 h-4 text-muted-foreground"></i>
                                <span class="text-sm">Contact Support</span>
                            </div>
                        </a>
                        <a href="#" class="block p-3 transition-colors border rounded-lg border-border hover:bg-muted">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="bug" class="w-4 h-4 text-muted-foreground"></i>
                                <span class="text-sm">Report a Bug</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Toggle Switch Styles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    cursor: pointer;
}

.toggle-slider {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: hsl(var(--border));
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-switch .toggle-slider {
    background: hsl(var(--primary));
}

input:checked + .toggle-switch .toggle-slider:before {
    transform: translateX(20px);
}

/* Interest Tags */
.interest-tag {
    transition: all 0.2s ease;
}

input:checked + .interest-tag {
    background: hsl(var(--primary));
    color: hsl(var(--primary-foreground));
    border-color: hsl(var(--primary));
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Focus states */
input:focus + .toggle-switch .toggle-slider {
    box-shadow: 0 0 0 2px hsl(var(--primary) / 0.2);
}

select:focus,
input[type="text"]:focus,
textarea:focus {
    border-color: hsl(var(--primary));
    box-shadow: 0 0 0 2px hsl(var(--primary) / 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .toggle-switch {
        width: 40px;
        height: 22px;
    }
    
    .toggle-slider:before {
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
    }
    
    input:checked + .toggle-switch .toggle-slider:before {
        transform: translateX(18px);
    }
}
</style>



@endsection