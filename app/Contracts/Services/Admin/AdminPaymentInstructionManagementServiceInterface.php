<?php

namespace App\Contracts\Services\Admin;

use App\Models\PaymentInstruction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminPaymentInstructionManagementServiceInterface
{
    public function listInstructions(array $filters): LengthAwarePaginator;

    public function findOrFail(string $id): PaymentInstruction;

    public function createInstruction(array $data): PaymentInstruction;

    public function updateInstruction(string $id, array $data): PaymentInstruction;

    public function activateInstruction(string $id): PaymentInstruction;

    public function deleteInstruction(string $id): bool;
}
