<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'usage_count'
    ];

    protected $casts = [
        'usage_count' => 'integer'
    ];

    // Polymorphic relationships
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function events(): MorphToMany
    {
        return $this->morphedByMany(Events::class, 'taggable');
    }

    // Add more models as needed
    // public function marketplace(): MorphToMany
    // {
    //     return $this->morphedByMany(MarketplaceItem::class, 'taggable');
    // }

    // Scopes
    public function scopePopular($query, $limit = 20)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }

    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    // Methods
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function decrementUsage(): void
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = \Illuminate\Support\Str::slug($tag->name);
            }
        });
    }

    // Helper method to find or create tags
    public static function findOrCreateTags(array $tagNames): array
    {
        $tags = [];
        
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) continue;

            $tag = static::firstOrCreate(
                ['name' => $tagName],
                ['slug' => \Illuminate\Support\Str::slug($tagName)]
            );
            
            $tags[] = $tag;
        }

        return $tags;
    }
}