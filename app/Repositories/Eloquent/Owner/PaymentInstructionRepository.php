<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\PaymentInstruction;
use App\Repositories\Interfaces\Owner\PaymentInstructionRepositoryInterface;

class PaymentInstructionRepository implements PaymentInstructionRepositoryInterface
{
    public function __construct(
        protected PaymentInstruction $model
    ) {}

    public function findActive(): ?PaymentInstruction
    {
        return $this->model->newQuery()
            ->active()
            ->orderByDesc('updated_at')
            ->first();
    }
}
