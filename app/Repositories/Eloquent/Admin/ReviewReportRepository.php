<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\ReviewReport;
use App\Repositories\Interfaces\Admin\ReviewReportRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewReportRepository implements ReviewReportRepositoryInterface
{
    public function __construct(
        protected ReviewReport $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['review:id,rating,comment,salon_id', 'reporter:id,name']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['per_page'] ?? 15)));

        return $query->orderByDesc('created_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?ReviewReport
    {
        return $this->model->newQuery()->find($id);
    }

    public function update(ReviewReport $report, array $data): ReviewReport
    {
        $report->update($data);

        return $report->fresh(['review', 'reporter:id,name']);
    }
}
