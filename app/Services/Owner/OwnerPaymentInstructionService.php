<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerPaymentInstructionServiceInterface;
use App\Repositories\Interfaces\Owner\PaymentInstructionRepositoryInterface;

class OwnerPaymentInstructionService implements OwnerPaymentInstructionServiceInterface
{
    public function __construct(
        protected PaymentInstructionRepositoryInterface $paymentInstructionRepository,
    ) {}

    public function getActiveInstruction(): ?array
    {
        $instruction = $this->paymentInstructionRepository->findActive();

        if (! $instruction) {
            return null;
        }

        return [
            'id' => $instruction->id,
            'title' => $instruction->title,
            'bank_name' => $instruction->bank_name,
            'account_number' => $instruction->account_number,
            'account_holder' => $instruction->account_holder,
            'transfer_content' => $instruction->transfer_content,
            'qr_code_image' => $instruction->qr_code_image,
            'qr_code_image_url' => $this->resolveStorageUrl($instruction->qr_code_image),
            'content' => $instruction->content,
            'updated_at' => $instruction->updated_at?->format(DATE_ATOM),
        ];
    }

    protected function resolveStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.$path;
    }
}
