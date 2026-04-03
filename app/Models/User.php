<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, CanResetPassword
{
    use HasFactory, HasUuidPrimaryKey, Notifiable, SoftDeletes, CanResetPasswordTrait;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'role_id',
        'owner_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar_url',
        'status',
        'email_verified_at',
        'language',
        'token_version',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'token_version' => 'integer',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'token_version' => $this->token_version,
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function staffUsers(): HasMany
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    public function staffProfile(): HasOne
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    public function ownedSalon(): HasOne
    {
        return $this->hasOne(Salon::class, 'owner_id');
    }

    public function ownedSalons(): HasMany
    {
        return $this->hasMany(Salon::class, 'owner_id');
    }

    /** @deprecated Use ownedSalon() — MVP 1 salon/owner */
    public function salon(): HasOne
    {
        return $this->ownedSalon();
    }

    /** @deprecated Use ownedSalons() */
    public function salons(): HasMany
    {
        return $this->ownedSalons();
    }

    public function refreshTokens(): HasMany
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function bookingsAsCustomer(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookingsCreated(): HasMany
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function reviewReports(): HasMany
    {
        return $this->hasMany(ReviewReport::class, 'reporter_id');
    }

    public function favoriteSalons(): BelongsToMany
    {
        return $this->belongsToMany(Salon::class, 'favorite_salons')
            ->withPivot('created_at');
    }

    public function favoriteProducts(): HasMany
    {
        return $this->hasMany(FavoriteProduct::class);
    }

    public function recentlyViewed(): HasMany
    {
        return $this->hasMany(RecentlyViewed::class);
    }

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'owner_id');
    }

    public function ownerNotificationBroadcasts(): HasMany
    {
        return $this->hasMany(OwnerNotificationBroadcast::class, 'owner_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeWithRole($query, string $roleName)
    {
        return $query->whereHas('role', fn ($q) => $q->where('name', $roleName));
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role?->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isOwner(): bool
    {
        return $this->hasRole(Role::OWNER);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(Role::CUSTOMER);
    }

    public function isStaff(): bool
    {
        return $this->hasRole(Role::STAFF);
    }

    public function resolveStaffProfile(): ?Staff
    {
        return $this->relationLoaded('staffProfile')
            ? $this->staffProfile
            : $this->staffProfile()->first();
    }
}
