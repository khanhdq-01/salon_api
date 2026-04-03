<?php

namespace App\Contracts\Services\Owner;

interface OwnerPaymentInstructionServiceInterface
{
    public function getActiveInstruction(): ?array;
}
