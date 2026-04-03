<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HairstyleArticle extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    protected $table = 'hairstyle_articles';

    protected $fillable = [
        'salon_id',
        'title',
        'description',
        'image',
        'category',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
