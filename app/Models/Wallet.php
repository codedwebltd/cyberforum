<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_spent',
        'currency',
        'is_active',
        'last_transaction_at',
    ];

    protected $casts = [
        'balance' => 'integer',
        'pending_balance' => 'integer',
        'total_earned' => 'integer',
        'total_spent' => 'integer',
        'is_active' => 'boolean',
        'last_transaction_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors (convert cents to dollars)
    public function getBalanceInDollarsAttribute(): float
    {
        return $this->balance / 100;
    }

    public function getPendingBalanceInDollarsAttribute(): float
    {
        return $this->pending_balance / 100;
    }

    public function getTotalEarnedInDollarsAttribute(): float
    {
        return $this->total_earned / 100;
    }

    public function getTotalSpentInDollarsAttribute(): float
    {
        return $this->total_spent / 100;
    }

    // Methods
    public function addFunds(int $amountInCents, string $description = null): bool
    {
        $this->increment('balance', $amountInCents);
        $this->increment('total_earned', $amountInCents);
        $this->update(['last_transaction_at' => now()]);

        return true;
    }

    public function deductFunds(int $amountInCents, string $description = null): bool
    {
        if ($this->balance < $amountInCents) {
            return false; // Insufficient funds
        }

        $this->decrement('balance', $amountInCents);
        $this->increment('total_spent', $amountInCents);
        $this->update(['last_transaction_at' => now()]);

        return true;
    }

    public function hasSufficientBalance(int $amountInCents): bool
    {
        return $this->balance >= $amountInCents;
    }
}
