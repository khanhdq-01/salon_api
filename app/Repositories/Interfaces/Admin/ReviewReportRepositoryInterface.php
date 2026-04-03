<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\ReviewReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReviewReportRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(string $id): ?ReviewReport;

    public function update(ReviewReport $report, array $data): ReviewReport;
}
