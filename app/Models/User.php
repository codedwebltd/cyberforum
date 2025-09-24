<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
    'name',
    'email',
    'password',
    'affiliate_id',
    'referral_code',
    'referred_by',
    'referrals_count',
    'deleted_at',
    'email_verified_at',
    'referrer_id',
    'last_active_at',
    'password_confirmation',
    'username',
    'bio',
    'avatar_url',
    'cover_url',
    'location',
    'website',
    'date_of_birth',
    'gender',
    'points',
    'reputation',
    'is_verified',
    'is_admin',
    'is_active',
    'profile_public',
    'show_email',
    'allow_messages',
    'show_online_status',      // ADD THIS
    'onboarding_completed',    // ADD THIS
    'profile_completion_percentage', // ADD THIS
    'location_history',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'date_of_birth' => 'date',
    'last_active_at' => 'datetime',
    'is_verified' => 'boolean',
    'is_admin' => 'boolean',
    'is_active' => 'boolean',
    'onboarding_completed' => 'boolean',
    'profile_public' => 'boolean',
    'show_email' => 'boolean',
    'allow_messages' => 'boolean',
    'show_online_status' => 'boolean',  // ADD THIS
    'password' => 'hashed',
    'location_history' => 'array',
];

    // Relationships
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function onboardingSteps(): HasMany
    {
        return $this->hasMany(OnboardingStep::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeOnlineRecently($query, $minutes = 15)
    {
        return $query->where('last_active_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeByReputation($query, $order = 'desc')
    {
        return $query->orderBy('reputation', $order);
    }

    public function scopeByPoints($query, $order = 'desc')
    {
        return $query->orderBy('points', $order);
    }

    public function scopeCompletedProfile($query)
    {
        return $query->where('profile_completion_percentage', '>=', 80);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->username ?: $this->name;
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_active_at && $this->last_active_at->diffInMinutes(now()) <= 15;
    }

    // Methods
    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => now()]);
    }

    public function incrementPoints(int $points): void
    {
        $this->increment('points', $points);
    }

    public function calculateProfileCompletion(): int
{
    $fields = [
        'name' => !empty($this->name),
        'email' => !empty($this->email),
        'avatar_url' => !empty($this->avatar_url),
        'bio' => !empty($this->bio),
        'location' => !empty($this->location),
        'website' => !empty($this->website),
        'date_of_birth' => !empty($this->date_of_birth),
        'gender' => !empty($this->gender)
    ];

    $completed = collect($fields)->filter()->count();
    $total = count($fields);
    $percentage = round(($completed / $total) * 100);

    Log::info('Profile completion calculated', [
        'user_id' => $this->id,
        'completed_fields' => $completed,
        'total_fields' => $total,
        'percentage' => $percentage,
        'field_status' => $fields
    ]);

    $this->update(['profile_completion_percentage' => $percentage]);

    return $percentage;
}

    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed;
    }


    // Add this method to your User model

public function getSecurityMetrics(int $days = 7): array
{
    $history = collect($this->location_history ?? [])
        ->filter(function($item) use ($days) {
            return isset($item['timestamp']) && 
                   \Carbon\Carbon::parse($item['timestamp'])->isAfter(now()->subDays($days));
        });

    $failedLogins = $history->where('action', 'failed_login');
    $successfulLogins = $history->where('action', 'login');

    // Calculate risk score (0-100, higher = more risky)
    $riskScore = $this->calculateRiskScore($failedLogins, $successfulLogins, $history);

    return [
        'period_days' => $days,
        'failed_logins' => $failedLogins->count(),
        'successful_logins' => $successfulLogins->count(),
        'total_attempts' => $history->count(),
        'success_rate' => $history->count() > 0 ? round(($successfulLogins->count() / $history->count()) * 100, 1) : 100,
        'unique_ips' => $history->pluck('ip')->unique()->count(),
        'unique_countries' => $history->pluck('location.country.name')->unique()->filter()->count(),
        'unique_cities' => $history->pluck('location.city.name')->unique()->filter()->count(),
        'unique_devices' => $history->pluck('location.device.device_type')->unique()->filter()->count(),
        'unique_browsers' => $history->pluck('location.device.browser')->unique()->filter()->count(),
        'tor_usage' => $history->where('location.security.is_tor', true)->count(),
        'vpn_usage' => $history->where('location.security.is_vpn', true)->count(),
        'proxy_usage' => $history->where('location.security.is_anonymous_proxy', true)->count(),
        'risk_score' => $riskScore,
        'risk_level' => $this->getRiskLevel($riskScore),
        'last_activity' => $history->sortByDesc('timestamp')->first()['timestamp'] ?? null,
    ];
}

private function calculateRiskScore($failedLogins, $successfulLogins, $allHistory): int
{
    $score = 0;
    $totalAttempts = $allHistory->count();
    
    if ($totalAttempts == 0) return 0;

    // Failed login ratio (0-40 points)
    $failureRate = $failedLogins->count() / $totalAttempts;
    $score += min(40, $failureRate * 100);

    // Multiple IP addresses (0-20 points)
    $uniqueIps = $allHistory->pluck('ip')->unique()->count();
    if ($uniqueIps > 3) $score += min(20, ($uniqueIps - 3) * 5);

    // Multiple countries (0-15 points)
    $uniqueCountries = $allHistory->pluck('location.country.name')->unique()->filter()->count();
    if ($uniqueCountries > 2) $score += min(15, ($uniqueCountries - 2) * 7);

    // Tor/VPN/Proxy usage (0-15 points)
    $anonymousConnections = $allHistory->filter(function($item) {
        return isset($item['location']['security']) && (
            $item['location']['security']['is_tor'] ||
            $item['location']['security']['is_vpn'] ||
            $item['location']['security']['is_anonymous_proxy']
        );
    })->count();
    
    if ($anonymousConnections > 0) {
        $score += min(15, ($anonymousConnections / $totalAttempts) * 30);
    }

    // Night time logins (0-10 points) - unusual hours
    $nightLogins = $allHistory->filter(function($item) {
        if (!isset($item['timestamp'])) return false;
        $hour = \Carbon\Carbon::parse($item['timestamp'])->hour;
        return $hour >= 2 && $hour <= 5; // 2 AM to 5 AM
    })->count();
    
    if ($nightLogins > 0) {
        $score += min(10, ($nightLogins / $totalAttempts) * 20);
    }

    return min(100, round($score));
}

private function getRiskLevel(int $score): string
{
    if ($score >= 70) return 'high';
    if ($score >= 40) return 'medium';
    if ($score >= 20) return 'low';
    return 'minimal';
}
}
