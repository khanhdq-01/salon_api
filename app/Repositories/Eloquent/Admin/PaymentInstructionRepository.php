<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\PaymentInstruction;
use App\Repositories\Interfaces\Admin\PaymentInstructionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PaymentInstructionRepository implements PaymentInstructionRepositoryInterface
{
    public function __construct(
        protected PaymentInstruction $model
    ) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $this->applyFilters($query, $filters);

        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($filters['limit'] ?? $filters['per_page'] ?? 15)));

        return $query->orderByDesc('updated_at')->paginate(perPage: $perPage, page: $page);
    }

    public function findById(string $id): ?PaymentInstruction
    {
        return $this->model->newQuery()->find($id);
    }

    public function exists(): bool
    {
        return $this->model->newQuery()->exists();
    }

    public function create(array $data): PaymentInstruction
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(PaymentInstruction $instruction, array $data): PaymentInstruction
    {
        $instruction->update($data);

        return $instruction->fresh();
    }

    public function deactivateOthers(string $activeId): void
    {
        $this->model->newQuery()
            ->where('id', '!=', $activeId)
            ->where('status', PaymentInstruction::STATUS_ACTIVE)
            ->update(['status' => PaymentInstruction::STATUS_INACTIVE]);
    }

    public function delete(PaymentInstruction $instruction): bool
    {
        return (bool) $instruction->delete();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['q'])) {
            $term = '%'.$filters['q'].'%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('bank_name', 'like', $term)
                    ->orWhere('account_holder', 'like', $term);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
