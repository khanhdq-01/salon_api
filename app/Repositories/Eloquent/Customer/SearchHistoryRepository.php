<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\Salon;
use App\Models\SearchHistory;
use App\Models\User;
use App\Repositories\Interfaces\Customer\SearchHistoryRepositoryInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class SearchHistoryRepository implements SearchHistoryRepositoryInterface
{
    private const MAX_ITEMS = 5;

    private static function salonRelations(): array
    {
        return [
            'salon.images' => fn ($query) => $query->orderByDesc('created_at'),
        ];
    }

    private static function salonImageRelations(): array
    {
        return [
            'images' => fn ($query) => $query->orderByDesc('created_at'),
        ];
    }

    public function getRecent(User $user, int $limit = self::MAX_ITEMS): Collection
    {
        return SearchHistory::query()
            ->with(self::salonRelations())
            ->where('user_id', $user->id)
            ->orderByDesc('searched_at')
            ->limit($limit)
            ->get();
    }

    public function recordQuery(User $user, string $query): SearchHistory
    {
        $query = trim($query);

        if ($query === '') {
            throw new InvalidArgumentException('Search query cannot be empty.');
        }

        SearchHistory::query()
            ->where('user_id', $user->id)
            ->where('type', SearchHistory::TYPE_QUERY)
            ->whereRaw('LOWER(query) = ?', [mb_strtolower($query)])
            ->delete();

        $item = SearchHistory::create([
            'user_id' => $user->id,
            'type' => SearchHistory::TYPE_QUERY,
            'query' => $query,
            'searched_at' => now(),
        ]);

        $this->trimHistory($user);

        return $item->load(self::salonRelations());
    }

    public function recordSalon(User $user, string $salonId): SearchHistory
    {
        $salon = Salon::query()
            ->with(self::salonImageRelations())
            ->find($salonId);

        if (! $salon) {
            throw new InvalidArgumentException('Salon not found.');
        }

        SearchHistory::query()
            ->where('user_id', $user->id)
            ->where('type', SearchHistory::TYPE_SALON)
            ->where('salon_id', $salon->id)
            ->delete();

        $item = SearchHistory::create([
            'user_id' => $user->id,
            'type' => SearchHistory::TYPE_SALON,
            'query' => $salon->name,
            'salon_id' => $salon->id,
            'searched_at' => now(),
        ]);

        $this->trimHistory($user);

        return $item->load(self::salonRelations());
    }

    private function trimHistory(User $user): void
    {
        $keepIds = SearchHistory::query()
            ->where('user_id', $user->id)
            ->orderByDesc('searched_at')
            ->limit(self::MAX_ITEMS)
            ->pluck('id');

        SearchHistory::query()
            ->where('user_id', $user->id)
            ->whereNotIn('id', $keepIds)
            ->delete();
    }
}
