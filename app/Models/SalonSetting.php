<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalonSetting extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public const DEFAULT_AUTO_CONFIRM_BOOKING = false;
    public const DEFAULT_CUSTOMER_CANCEL_BEFORE_MINUTES = 30;
    public const DEFAULT_BOOKING_INTERVAL_MINUTES = 30;
    public const DEFAULT_AUTO_APPROVE_WORK_SCHEDULE = false;

    public const ALLOWED_CANCEL_BEFORE_MINUTES = [30, 60, 120, 1440];
    public const ALLOWED_BOOKING_INTERVAL_MINUTES = [15, 30, 60];

    protected $fillable = [
        'salon_id',
        'auto_confirm_booking',
        'customer_cancel_before_minutes',
        'booking_interval_minutes',
        'auto_approve_work_schedule',
    ];

    protected function casts(): array
    {
        return [
            'auto_confirm_booking' => 'boolean',
            'customer_cancel_before_minutes' => 'integer',
            'booking_interval_minutes' => 'integer',
            'auto_approve_work_schedule' => 'boolean',
        ];
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public static function defaultAttributes(string $salonId): array
    {
        return [
            'salon_id' => $salonId,
            'auto_confirm_booking' => self::DEFAULT_AUTO_CONFIRM_BOOKING,
            'customer_cancel_before_minutes' => self::DEFAULT_CUSTOMER_CANCEL_BEFORE_MINUTES,
            'booking_interval_minutes' => self::DEFAULT_BOOKING_INTERVAL_MINUTES,
            'auto_approve_work_schedule' => self::DEFAULT_AUTO_APPROVE_WORK_SCHEDULE,
        ];
    }

    public function customerCancelDeadline(Booking $booking): Carbon
    {
        $appointment = Carbon::parse(
            sprintf('%s %s', $booking->booking_date, substr((string) $booking->booking_time, 0, 8))
        );

        return $appointment->copy()->subMinutes($this->customer_cancel_before_minutes);
    }

    public function customerCanCancelBooking(Booking $booking): bool
    {
        if (! in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true)) {
            return false;
        }

        return now()->lte($this->customerCancelDeadline($booking));
    }
}
