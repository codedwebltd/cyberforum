<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'comment_notifications',
        'like_notifications',
        'follow_notifications',
        'mention_notifications',
        'marketing_emails',
        'profile_visibility',
        'show_online_status',
        'allow_friend_requests',
        'allow_messages_from_strangers',
        'content_preferences',
        'content_language',
        'timezone',
        'theme',
        'two_factor_enabled',
        'login_alerts'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'comment_notifications' => 'boolean',
        'like_notifications' => 'boolean',
        'follow_notifications' => 'boolean',
        'mention_notifications' => 'boolean',
        'marketing_emails' => 'boolean',
        'show_online_status' => 'boolean',
        'allow_friend_requests' => 'boolean',
        'allow_messages_from_strangers' => 'boolean',
        'content_preferences' => 'array',
        'two_factor_enabled' => 'boolean',
        'login_alerts' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
