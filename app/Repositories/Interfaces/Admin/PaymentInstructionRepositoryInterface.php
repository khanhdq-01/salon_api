<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\PaymentInstruction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentInstructionRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(string $id): ?PaymentInstruction;

    public function exists(): bool;

    public function create(array $data): PaymentInstruction;

    public function update(PaymentInstruction $instruction, array $data): PaymentInstruction;

    public function deactivateOthers(string $activeId): void;

    public function delete(PaymentInstruction $instruction): bool;
}
