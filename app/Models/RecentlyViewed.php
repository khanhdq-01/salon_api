<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecentlyViewed extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const UPDATED_AT = null;
    public const CREATED_AT = null;

    protected $table = 'recently_viewed';

    protected $fillable = [
        'user_id',
        'salon_id',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function scopeForUser($query, string $userId)
    {
        return $query->where('user_id', $userId)->orderByDesc('viewed_at');
    }
}
