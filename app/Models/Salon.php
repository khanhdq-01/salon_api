<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use App\Support\SalonPublicVisibility;
use App\Support\SubscriptionExpiry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Salon extends Model
{
    use HasFactory, HasUuidPrimaryKey, SoftDeletes;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    protected $fillable = [
        'owner_id',
        'requested_package_id',
        'name',
        'slug',
        'description',
        'address',
        'lat',
        'lng',
        'phone',
        'image_url',
        'open_time',
        'close_time',
        'status',
        'approval_status',
        'is_locked',
        'rating_avg',
        'rating_count',
        'bookings_count',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'rating_avg' => 'decimal:2',
            'rating_count' => 'integer',
            'bookings_count' => 'integer',
            'is_locked' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Salon $salon) {
            if (empty($salon->slug) && ! empty($salon->name)) {
                $salon->slug = Str::slug($salon->name) . '-' . Str::lower(Str::random(6));
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function requestedPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'requested_package_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(SalonImage::class)->orderByDesc('created_at');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(SalonSchedule::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_salons')
            ->withPivot('created_at');
    }

    public function recentlyViewedEntries(): HasMany
    {
        return $this->hasMany(RecentlyViewed::class);
    }

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function notificationBroadcasts(): HasMany
    {
        return $this->hasMany(OwnerNotificationBroadcast::class);
    }

    public function settings(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SalonSetting::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', self::APPROVAL_APPROVED);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopePubliclyVisible($query)
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        return SalonPublicVisibility::applyPublicScope($query);
    }

    public function scopeMinRating($query, float $rating)
    {
        return $query->where('rating_avg', '>=', $rating);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        $like = '%' . $term . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('address', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    public function isPubliclyVisible(): bool
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        return SalonPublicVisibility::isPublic($this);
    }

    /** @deprecated Use isPubliclyVisible() */
    public function isVisibleToPublic(): bool
    {
        return $this->isPubliclyVisible();
    }
}
