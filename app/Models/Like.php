<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
        'type',
        'is_active',
        'metadata',
        'created_at', // Add this
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForModel($query, $model)
    {
        return $query->where('likeable_type', get_class($model))
                    ->where('likeable_id', $model->id);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public static function toggle(User $user, $likeable, string $type = 'like'): bool
{
    $existing = static::where([
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => get_class($likeable),
    ])->first();

    if ($existing) {
        $existing->delete();
        $likeable->decrement('likes_count');
        return false; // Unlike
    }

    static::create([
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => get_class($likeable),
        'type' => $type,
        'created_at' => now(), // Add this
        'updated_at' => now(), // Add this
    ]);

    $likeable->increment('likes_count');
    return true; // Liked
}
}
