<?php

namespace App\Models;

use App\Traits\LogsUserActivity; // Add this import
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, LogsUserActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_admin',
        'date_of_birth',
        'gender',
        'last_login_at',
        'last_login_ip',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'preferences' => 'array',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    // Add helper methods for role checking
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'developer']) || $this->is_admin;
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' && !$this->is_admin;
    }

    /**
     * Scope to get only admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true)
            ->orWhereIn('role', ['admin', 'developer']);
    }

    /**
     * Scope to get only customer users.
     */
    public function scopeCustomers($query)
    {
        return $query->where('is_admin', false)
            ->where('role', 'customer');
    }

    /**
     * Boot method to add event listeners for activity logging
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            self::logActivity('created', $user, [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);
        });

        static::updated(function ($user) {
            $changes = array_intersect_key($user->getChanges(), array_flip(['name', 'email', 'phone']));
            if (!empty($changes)) {
                self::logActivity('updated', $user, $changes);
            }
            if ($user->wasChanged()) {
                $user->logProfileChange($user->getChanges()); // This will now work
            }
        });

        static::deleted(function ($user) {
            self::logActivity('deleted', $user, [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);
        });
    }

    /**
     * Log user activity
     */
    private static function logActivity(string $event, User $user, array $properties = []): void
    {
        Log::info("User {$event}", [
            'user_id' => $user->id,
            'event' => $event,
            'properties' => $properties,
            'timestamp' => now(),
        ]);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(WishList::class);
    }

    public function getDefaultShippingAddressAttribute()
    {
        return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first()
            ?? $this->addresses()->where('type', 'both')->where('is_default', true)->first();
    }

    public function getDefaultBillingAddressAttribute()
    {
        return $this->addresses()->where('type', 'billing')->where('is_default', true)->first()
            ?? $this->addresses()->where('type', 'both')->where('is_default', true)->first();
    }

    /**
     * Get user avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->getStorageImageUrl($this->avatar, 150, 150);
        }

        return $this->getFirstMediaUrl('avatars');
    }

    /**
     * Get large avatar URL
     */
    public function getLargeAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->getStorageImageUrl($this->avatar, 300, 300);
        }

        return $this->getMediaStorageUrl('avatars', 'large');
    }
}
