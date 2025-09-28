<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'location',
        'venue',
        'start_datetime',
        'end_datetime',
        'timezone',
        'visibility',
        'is_free',
        'price',
        'max_attendees',
        'current_attendees',
        'image_url',
        'gallery',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'gallery' => 'array',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
        
        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Route key
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Accessors
    public function getFormattedDateAttribute(): string
    {
        return $this->start_datetime->format('M j, Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->start_datetime->format('g:i A');
    }

    public function getFormattedDateTimeAttribute(): string
    {
        return $this->start_datetime->format('M j, Y \a\t g:i A');
    }

    public function getDurationAttribute(): string
    {
        $diff = $this->start_datetime->diff($this->end_datetime);
        
        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
        } else {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        }
    }

    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_attendees) {
            return null;
        }
        
        return max(0, $this->max_attendees - $this->current_attendees);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->max_attendees && $this->current_attendees >= $this->max_attendees;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now());
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_datetime', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    // Methods
    public function isUpcoming(): bool
    {
        return $this->start_datetime->isFuture();
    }

    public function isOngoing(): bool
    {
        return now()->between($this->start_datetime, $this->end_datetime);
    }

    public function isPast(): bool
    {
        return $this->end_datetime->isPast();
    }

    public function isToday()
    {
        return $this->start_datetime->isToday();
    }

    public function isTomorrow()
    {
        return $this->start_datetime->isTomorrow();
    }

    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    public function isConnectionsOnly(): bool
    {
        return $this->visibility === 'connections';
    }

    // Category constants
    public const CATEGORY_WEDDING = 'wedding';
    public const CATEGORY_BIRTHDAY = 'birthday';
    public const CATEGORY_SEMINAR = 'seminar';
    public const CATEGORY_CONFERENCE = 'conference';
    public const CATEGORY_WORKSHOP = 'workshop';
    public const CATEGORY_PARTY = 'party';
    public const CATEGORY_MEETING = 'meeting';
    public const CATEGORY_FESTIVAL = 'festival';
    public const CATEGORY_CONCERT = 'concert';
    public const CATEGORY_SPORTS = 'sports';
    public const CATEGORY_OTHER = 'other';

    public static function getCategories(): array
    {
        return [
            self::CATEGORY_WEDDING => 'Wedding',
            self::CATEGORY_BIRTHDAY => 'Birthday',
            self::CATEGORY_SEMINAR => 'Seminar',
            self::CATEGORY_CONFERENCE => 'Conference',
            self::CATEGORY_WORKSHOP => 'Workshop',
            self::CATEGORY_PARTY => 'Party',
            self::CATEGORY_MEETING => 'Meeting',
            self::CATEGORY_FESTIVAL => 'Festival',
            self::CATEGORY_CONCERT => 'Concert',
            self::CATEGORY_SPORTS => 'Sports',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    // Visibility constants
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_CONNECTIONS = 'connections';
    public const VISIBILITY_PRIVATE = 'private';

    public static function getVisibilityOptions(): array
    {
        return [
            self::VISIBILITY_PUBLIC => 'Public',
            self::VISIBILITY_CONNECTIONS => 'Connections Only',
            self::VISIBILITY_PRIVATE => 'Private',
        ];
    }

    public function tags()
{
    return $this->morphToMany(Tag::class, 'taggable');
}
}