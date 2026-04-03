<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OwnerNotificationBroadcast extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public $timestamps = false;

    public const UPDATED_AT = null;
    public const CREATED_AT = 'created_at';

    protected $fillable = [
        'salon_id',
        'owner_id',
        'type',
        'title',
        'content',
        'recipient_count',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'recipient_count' => 'integer',
        'created_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function isSent(): bool
    {
        return $this->sent_at !== null;
    }

    public function isPending(): bool
    {
        return $this->sent_at === null;
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
