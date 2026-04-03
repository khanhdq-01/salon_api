<?php

namespace App\Support;

use App\Models\Salon;

class SalonImageResolver
{
    public static function resolve(?Salon $salon): ?string
    {
        if (! $salon) {
            return null;
        }

        if ($salon->relationLoaded('images') && $salon->images->isNotEmpty()) {
            return $salon->images->first()->image_url ?: null;
        }

        return $salon->image_url ?: null;
    }
}
