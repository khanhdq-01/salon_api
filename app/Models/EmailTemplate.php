<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const KEY_EXPIRY_7_DAYS = 'subscription_expiry_7_days';
    public const KEY_EXPIRY_3_DAYS = 'subscription_expiry_3_days';
    public const KEY_EXPIRED = 'subscription_expired';
    public const KEY_SUBSCRIPTION_APPROVED = 'subscription_approved';
    public const KEY_BOOKING_REQUEST = 'booking_request';
    public const KEY_BOOKING_CONFIRMED = 'booking_confirmed';

    public const KEYS = [
        self::KEY_EXPIRY_7_DAYS,
        self::KEY_EXPIRY_3_DAYS,
        self::KEY_EXPIRED,
        self::KEY_SUBSCRIPTION_APPROVED,
        self::KEY_BOOKING_REQUEST,
        self::KEY_BOOKING_CONFIRMED,
    ];

    protected $fillable = [
        'template_key',
        'template_name',
        'subject',
        'content',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
