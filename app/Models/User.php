<?php
//
//    namespace App\Models;
//
//    use Illuminate\Contracts\Auth\MustVerifyEmail;
//    use Illuminate\Database\Eloquent\Factories\HasFactory;
//    use Illuminate\Database\Eloquent\Relations\HasMany;
//    use Illuminate\Foundation\Auth\User as Authenticatable;
//    use Illuminate\Notifications\Notifiable;
//    use Laravel\Sanctum\HasApiTokens;
//    use Spatie\Permission\Traits\HasRoles;
//    use Illuminate\Support\Facades\Log;
//
//    class User extends Authenticatable implements MustVerifyEmail
//    {
//        use HasApiTokens, HasFactory, Notifiable, HasRoles;
//
//        protected $fillable = [
//            'name',
//            'email',
//            'phone',
//            'password',
//            'is_admin',
//            'date_of_birth',
//            'gender',
//            'last_login_at',
//            'preferences',
//        ];
//
//        protected $hidden = [
//            'password',
//            'remember_token',
//            'two_factor_recovery_codes',
//            'two_factor_secret',
//        ];
//
//        protected $casts = [
//            'email_verified_at' => 'datetime',
//            'date_of_birth' => 'date',
//            'last_login_at' => 'datetime',
//            'preferences' => 'array',
//            'is_admin' => 'boolean',
//            'password' => 'hashed',
//        ];
//
//        /**
//         * Boot method to add event listeners for activity logging
//         */
//        protected static function boot()
//        {
//            parent::boot();
//
//            static::created(function ($user) {
//                self::logActivity('created', $user, [
//                    'name' => $user->name,
//                    'email' => $user->email,
//                    'phone' => $user->phone,
//                ]);
//            });
//
//            static::updated(function ($user) {
//                $changes = array_intersect_key($user->getChanges(), array_flip(['name', 'email', 'phone']));
//                if (!empty($changes)) {
//                    self::logActivity('updated', $user, $changes);
//                }
//            });
//
//            static::deleted(function ($user) {
//                self::logActivity('deleted', $user, [
//                    'name' => $user->name,
//                    'email' => $user->email,
//                    'phone' => $user->phone,
//                ]);
//            });
//        }
//
//        /**
//         * Log user activity
//         */
//        private static function logActivity(string $event, User $user, array $properties = [])
//        {
//            Log::info("User {$event}", [
//                'user_id' => $user->id,
//                'event' => $event,
//                'properties' => $properties,
//                'timestamp' => now(),
//            ]);
//        }
//
//        public function orders(): HasMany
//        {
//            return $this->hasMany(Order::class);
//        }
//
//        public function addresses(): HasMany
//        {
//            return $this->hasMany(UserAddress::class);
//        }
//
//        public function reviews(): HasMany
//        {
//            return $this->hasMany(ProductReview::class);
//        }
//
//        public function wishlist(): HasMany
//        {
//            return $this->hasMany(WishList::class, 'user_id', 'id');
//        }
//
//        public function getDefaultShippingAddressAttribute()
//        {
//            return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first()
//                ?? $this->addresses()->where('type', 'both')->where('is_default', true)->first();
//        }
//
//        public function getDefaultBillingAddressAttribute()
//        {
//            return $this->addresses()->where('type', 'billing')->where('is_default', true)->first()
//                ?? $this->addresses()->where('type', 'both')->where('is_default', true)->first();
//        }
//    }


namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'date_of_birth',
        'gender',
        'last_login_at',
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
    private static function logActivity(string $event, User $user, array $properties = [])
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
}
