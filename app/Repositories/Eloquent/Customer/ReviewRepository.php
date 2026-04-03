<?php

namespace App\Repositories\Eloquent\Customer;

use App\Repositories\Interfaces\Customer\ReviewRepositoryInterface;
use App\Models\Review;
use App\Models\ReviewReport;
use App\Models\Salon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        protected Review $model
    ) {}

    public function findById(string $id, array $relations = []): ?Review
    {
        return $this->model->newQuery()->with($relations)->find($id);
    }

    public function findByBookingId(string $bookingId): ?Review
    {
        return $this->model->newQuery()->where('booking_id', $bookingId)->first();
    }

    public function paginateBySalon(string $salonId, array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['customer:id,name,avatar_url'])
            ->where('salon_id', $salonId);

        if ($filters['rating_min'] !== null) {
            $query->minRating($filters['rating_min']);
        }

        return $query
            ->orderByDesc('created_at')
            ->paginate(perPage: $filters['per_page'], page: $filters['page']);
    }

    public function create(array $data): Review
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Review $review, array $data): Review
    {
        $review->update($data);

        return $review->fresh(['customer:id,name,avatar_url']);
    }

    public function delete(Review $review): bool
    {
        return (bool) $review->delete();
    }

    public function createReport(Review $review, array $data, string $reporterId): ReviewReport
    {
        return ReviewReport::query()->create([
            'review_id' => $review->id,
            'reporter_id' => $reporterId,
            'reason' => $data['reason'],
            'status' => $data['status'] ?? ReviewReport::STATUS_PENDING,
        ]);
    }

    public function getSalonRatingStats(string $salonId): array
    {
        $stats = $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total')
            ->first();

        return [
            'avg' => round((float) ($stats->avg_rating ?? 0), 2),
            'count' => (int) ($stats->total ?? 0),
        ];
    }

    public function getSalonRatingDistribution(string $salonId): array
    {
        $rows = $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $distribution = [];
        for ($star = 1; $star <= 5; $star++) {
            $distribution[(string) $star] = (int) ($rows[$star] ?? 0);
        }

        return $distribution;
    }

    public function updateSalonRating(string $salonId, float $avg, int $count): void
    {
        Salon::query()->whereKey($salonId)->update([
            'rating_avg' => $avg,
            'rating_count' => $count,
        ]);
    }
}
