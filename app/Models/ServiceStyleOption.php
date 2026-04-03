<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceStyleOption extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    protected $fillable = [
        'service_id',
        'name',
        'gender',
        'description',
        'article',
        'extra_price',
        'extra_duration',
        'image',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'extra_price' => 'integer',
        'extra_duration' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingService::class, 'service_style_option_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
