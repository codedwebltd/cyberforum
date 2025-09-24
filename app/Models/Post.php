<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'excerpt',
        'featured_image_url',
        'type',
        'status',
        'is_pinned',
        'is_featured',
        'allow_comments',
        'is_approved',
        'slug',
        'tags',
        'mentions',
        'attachments',
        'published_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'is_approved' => 'boolean',
        'is_reported' => 'boolean',
        'tags' => 'array',
        'mentions' => 'array',
        'attachments' => 'array',
        'published_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc')->orderBy('views_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_activity_at', 'desc');
    }

    public function scopeTrending($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('comments_count', 'desc');
    }

    // Methods
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($post) {
        if (empty($post->slug)) {
            $post->slug = static::generateUniqueSlug($post->title);
        }
    });
}

public static function generateUniqueSlug($title)
{
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $counter = 1;

    while (static::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

public function getRouteKeyName()
{
    return 'slug';
}
}
