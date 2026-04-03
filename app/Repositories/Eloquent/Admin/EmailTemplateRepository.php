<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\EmailTemplate;
use App\Repositories\Interfaces\Admin\EmailTemplateRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EmailTemplateRepository implements EmailTemplateRepositoryInterface
{
    public function __construct(
        protected EmailTemplate $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query->orderBy('template_key')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?EmailTemplate
    {
        return $this->model->newQuery()->find($id);
    }

    public function update(EmailTemplate $template, array $data): EmailTemplate
    {
        $template->update($data);

        return $template->fresh();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('template_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }
    }
}
