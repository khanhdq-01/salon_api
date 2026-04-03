<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'booking_id',
        'service_id',
        'service_style_option_id',
        'price',
        'duration_minutes',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'duration_minutes' => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function styleOption(): BelongsTo
    {
        return $this->belongsTo(ServiceStyleOption::class, 'service_style_option_id');
    }
}
