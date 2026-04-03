<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, HasUuidPrimaryKey, SoftDeletes;

    public const BILLING_1_MONTH = '1_month';
    public const BILLING_3_MONTHS = '3_months';
    public const BILLING_1_YEAR = '1_year';

    protected $fillable = [
        'name',
        'type',
        'price',
        'billing_period',
        'description',
        'max_staff',
        'max_services',
        'max_bookings_per_month',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'max_staff' => 'integer',
            'max_services' => 'integer',
            'max_bookings_per_month' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculateEndDate(\Carbon\Carbon $startDate): \Carbon\Carbon
    {
        return match ($this->billing_period) {
            self::BILLING_3_MONTHS => $startDate->copy()->addMonths(3),
            self::BILLING_1_YEAR => $startDate->copy()->addYear(),
            default => $startDate->copy()->addMonth(),
        };
    }

    public function billingPeriodLabel(): string
    {
        return match ($this->billing_period) {
            self::BILLING_3_MONTHS => '3 tháng',
            self::BILLING_1_YEAR => '1 năm',
            default => '1 tháng',
        };
    }

    public function bookingsLimitLabel(): string
    {
        return 'Lịch đặt / '.$this->billingPeriodLabel().' tối đa';
    }

    public function monthlyEquivalentPrice(): int
    {
        return match ($this->billing_period) {
            self::BILLING_3_MONTHS => (int) round($this->price / 3),
            self::BILLING_1_YEAR => (int) round($this->price / 12),
            default => (int) $this->price,
        };
    }
}
