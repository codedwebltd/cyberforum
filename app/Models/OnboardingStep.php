<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'step_name',
        'is_completed',
        'completed_at',
        'step_order',
        'step_data',
        'attempts',
        'last_attempt_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'step_data' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByOrder($query)
    {
        return $query->orderBy('step_order');
    }

    // Methods
    public function markCompleted(array $data = []): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'step_data' => $data,
            'last_attempt_at' => now()
        ]);
    }

    public function incrementAttempt(): void
    {
        $this->increment('attempts');
        $this->update(['last_attempt_at' => now()]);
    }

    public function getProgressPercentage(): int
    {
        $user = $this->user;
        $totalSteps = $user->onboardingSteps()->count();
        $completedSteps = $user->onboardingSteps()->completed()->count();

        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }
}
