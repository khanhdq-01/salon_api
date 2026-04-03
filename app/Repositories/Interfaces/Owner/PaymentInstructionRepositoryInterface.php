<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\PaymentInstruction;

interface PaymentInstructionRepositoryInterface
{
    public function findActive(): ?PaymentInstruction;
}
