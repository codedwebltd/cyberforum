<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content',
        'attachments',
        'mentions',
        'path',
        'depth',
        'status',
        'is_approved',
    ];

    protected $casts = [
        'attachments' => 'array',
        'mentions' => 'array',
        'is_approved' => 'boolean',
        'is_reported' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
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

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeByDepth($query, $depth)
    {
        return $query->where('depth', $depth);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc');
    }

    // Methods
  public function updatePath(): void
{
    if ($this->parent_id) {
        $parent = $this->parent;
        if ($parent) {
            $this->path = $parent->path ? $parent->path . '.' . $this->id : $this->id;
            $this->depth = $parent->depth + 1;
        }
    } else {
        $this->path = (string) $this->id;
        $this->depth = 0;
    }
    $this->save();
}

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getAncestors(): array
    {
        if (!$this->path) return [];

        $ids = explode('.', $this->path);
        array_pop($ids); // Remove current comment ID

        return Comment::whereIn('id', $ids)->orderBy('depth')->get()->toArray();
    }
}
