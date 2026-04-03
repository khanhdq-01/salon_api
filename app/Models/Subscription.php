<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING_APPROVAL,
        self::STATUS_AWAITING_PAYMENT,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_ACTIVE,
        self::STATUS_EXPIRED,
        self::STATUS_CANCELLED,
    ];

    public const AWAITING_REVIEW_STATUSES = [
        self::STATUS_PENDING_APPROVAL,
        self::STATUS_AWAITING_PAYMENT,
    ];

    protected $fillable = [
        'owner_id',
        'package_id',
        'requested_package_id',
        'requested_amount',
        'requested_at',
        'payment_proof',
        'payment_note',
        'status',
        'start_date',
        'end_date',
        'auto_renew',
        'reviewed_at',
        'reviewed_by',
        'approved_at',
        'approved_by',
        'approved_amount',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'auto_renew' => 'boolean',
            'requested_amount' => 'integer',
            'approved_amount' => 'integer',
            'requested_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function requestedPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'requested_package_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeEffective($query)
    {
        return $query->whereIn('status', [
            self::STATUS_ACTIVE,
            self::STATUS_PENDING_APPROVAL,
            self::STATUS_AWAITING_PAYMENT,
            self::STATUS_REJECTED,
            self::STATUS_APPROVED,
        ]);
    }

    public function scopeForOwner($query, string $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    public function isPendingApproval(): bool
    {
        return in_array($this->status, self::AWAITING_REVIEW_STATUSES, true);
    }

    public function isAwaitingPayment(): bool
    {
        return $this->isPendingApproval();
    }
}
