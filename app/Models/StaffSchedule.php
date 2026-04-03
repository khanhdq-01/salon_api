<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffSchedule extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public const SUBMITTED_BY_OWNER = 'owner';
    public const SUBMITTED_BY_STAFF = 'staff';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'staff_id',
        'work_date',
        'start_time',
        'end_time',
        'status',
        'submitted_by',
        'note',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForSalon($query, string $salonId)
    {
        return $query->whereHas('staff', fn ($staffQuery) => $staffQuery->where('salon_id', $salonId));
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('work_date', $date);
    }
}
