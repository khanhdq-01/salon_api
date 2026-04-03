<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class SalonSearchQuery
{
    private const FULLTEXT_COLUMNS = 'name, address, description';

    public static function apply(Builder $query, string $term): void
    {
        $term = trim($term);

        if ($term === '') {
            return;
        }

        if (self::supportsFulltext()) {
            $boolean = self::toBooleanModeQuery($term);
            $like = '%' . self::escapeLike($term) . '%';

            $query->where(function (Builder $inner) use ($boolean, $like) {
                $inner->whereRaw(
                    'MATCH(' . self::FULLTEXT_COLUMNS . ') AGAINST (? IN BOOLEAN MODE)',
                    [$boolean]
                )
                    ->orWhere('name', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('owner', fn (Builder $ownerQuery) => $ownerQuery
                        ->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like))
                    ->orWhereHas('services', fn (Builder $serviceQuery) => $serviceQuery
                        ->active()
                        ->where('name', 'like', $like));
            });

            return;
        }

        $like = '%' . self::escapeLike($term) . '%';

        $query->where(function (Builder $inner) use ($like) {
            $inner->where('name', 'like', $like)
                ->orWhere('address', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('owner', fn (Builder $ownerQuery) => $ownerQuery
                    ->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like))
                ->orWhereHas('services', fn (Builder $serviceQuery) => $serviceQuery
                    ->active()
                    ->where('name', 'like', $like));
        });
    }

    public static function orderByRelevance(Builder $query, string $term): void
    {
        $term = trim($term);

        if ($term === '' || ! self::supportsFulltext()) {
            return;
        }

        $boolean = self::toBooleanModeQuery($term);

        $query->orderByRaw(
            'MATCH(' . self::FULLTEXT_COLUMNS . ') AGAINST (? IN BOOLEAN MODE) DESC',
            [$boolean]
        );
    }

    /**
     * Build BOOLEAN MODE query with prefix wildcards for near-match search.
     */
    public static function toBooleanModeQuery(string $term): string
    {
        $words = preg_split('/\s+/u', trim($term), -1, PREG_SPLIT_NO_EMPTY);
        $parts = [];

        foreach ($words as $word) {
            $clean = preg_replace('/[^\p{L}\p{N}]+/u', '', $word);

            if ($clean === '') {
                continue;
            }

            $parts[] = '+' . $clean . '*';
        }

        if ($parts === []) {
            $fallback = preg_replace('/[^\p{L}\p{N}]+/u', '', $term);

            return $fallback !== '' ? '+' . $fallback . '*' : '""';
        }

        return implode(' ', $parts);
    }

    private static function supportsFulltext(): bool
    {
        return DB::getDriverName() === 'mysql';
    }

    private static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value);
    }
}
