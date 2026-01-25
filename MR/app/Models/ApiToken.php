<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'token',
        'name',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the API token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client associated with the API token.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the token is valid.
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Update the last used timestamp.
     */
    public function updateLastUsedAt(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if the token has a specific ability.
     */
    public function can(string $ability): bool
    {
        return in_array('*', $this->abilities) || in_array($ability, $this->abilities);
    }

    /**
     * Get the token's abilities.
     */
    public function getAbilities(): array
    {
        return $this->abilities ?? [];
    }

    /**
     * Generate a new API token.
     */
    public static function generateToken(): string
    {
        return 'mr_' . Str::random(60);
    }

    /**
     * Create a new API token for a user.
     */
    public static function createForUser(User $user, array $attributes = []): self
    {
        return static::create(array_merge([
            'token' => static::generateToken(),
            'user_id' => $user->id,
            'abilities' => ['*'], // Default abilities
        ], $attributes));
    }

    /**
     * Find a token by its value.
     */
    public static function findByToken(string $token): ?self
    {
        return static::where('token', $token)->first();
    }

    /**
     * Find a valid token by its value.
     */
    public static function findValidToken(string $token): ?self
    {
        return static::where('token', $token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    /**
     * Revoke the token.
     */
    public function revoke(): void
    {
        $this->delete();
    }

    /**
     * Scope a query to only include tokens that are not expired.
     */
    public function scopeValid($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include expired tokens.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope a query to only include tokens for a specific user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope a query to only include tokens with a specific ability.
     */
    public function scopeWithAbility($query, string $ability)
    {
        return $query->whereJsonContains('abilities', $ability)
                    ->orWhereJsonContains('abilities', '*');
    }

    /**
     * Get the formatted token (masked for security).
     */
    public function getFormattedTokenAttribute(): string
    {
        if (!$this->token) {
            return '';
        }

        return substr($this->token, 0, 8) . '...' . substr($this->token, -8);
    }

    /**
     * Get the token's status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->last_used_at && $this->last_used_at->diffInDays(now()) > 30) {
            return 'inactive';
        }

        return 'active';
    }

    /**
     * Get the token's status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'yellow',
            'expired' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get the remaining days until expiration.
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return $this->expires_at->diffInDays(now(), false);
    }

    /**
     * Get the days since last use.
     */
    public function getDaysSinceLastUseAttribute(): int
    {
        if (!$this->last_used_at) {
            return 0;
        }

        return $this->last_used_at->diffInDays(now());
    }

    /**
     * Check if the token should be considered inactive.
     */
    public function isInactive(): bool
    {
        return $this->last_used_at && $this->last_used_at->diffInDays(now()) > 30;
    }

    /**
     * Get usage statistics.
     */
    public function getUsageStats(): array
    {
        return [
            'created_at' => $this->created_at,
            'last_used_at' => $this->last_used_at,
            'days_since_last_use' => $this->days_since_last_use,
            'remaining_days' => $this->remaining_days,
            'status' => $this->status,
            'is_expired' => $this->isExpired(),
            'is_inactive' => $this->isInactive(),
        ];
    }
}
