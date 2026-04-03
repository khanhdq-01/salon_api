<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminPaymentInstructionManagementServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\PaymentInstruction;
use App\Repositories\Interfaces\Admin\PaymentInstructionRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminPaymentInstructionManagementService implements AdminPaymentInstructionManagementServiceInterface
{
    public function __construct(
        protected PaymentInstructionRepositoryInterface $paymentInstructionRepository
    ) {}

    public function listInstructions(array $filters): LengthAwarePaginator
    {
        if (! empty($filters['status'])) {
            $filters['status'] = $this->normalizeStatus($filters['status']);
        }

        return $this->paymentInstructionRepository->paginate($filters);
    }

    public function findOrFail(string $id): PaymentInstruction
    {
        $instruction = $this->paymentInstructionRepository->findById($id);

        if (! $instruction) {
            throw new BusinessException('Payment instruction không tồn tại.', 'PAYMENT_INSTRUCTION_NOT_FOUND', 404);
        }

        return $instruction;
    }

    public function createInstruction(array $data): PaymentInstruction
    {
        if ($this->paymentInstructionRepository->exists()) {
            throw new BusinessException(
                'Chỉ được tạo một hướng dẫn thanh toán.',
                'PAYMENT_INSTRUCTION_LIMIT',
                422
            );
        }

        $status = $this->normalizeStatus($data['status'] ?? PaymentInstruction::STATUS_INACTIVE);

        $instruction = $this->paymentInstructionRepository->create([
            'title' => HtmlSanitizer::plainText($data['title']) ?? '',
            'bank_name' => HtmlSanitizer::plainText($data['bank_name'] ?? null),
            'account_number' => HtmlSanitizer::plainText($data['account_number'] ?? null),
            'account_holder' => HtmlSanitizer::plainText($data['account_holder'] ?? null),
            'transfer_content' => HtmlSanitizer::plainText($data['transfer_content'] ?? null),
            'qr_code_image' => $data['qr_code_image'] ?? null,
            'content' => HtmlSanitizer::richHtml($data['content'] ?? null),
            'status' => $status,
        ]);

        if ($status === PaymentInstruction::STATUS_ACTIVE) {
            $this->paymentInstructionRepository->deactivateOthers($instruction->id);
        }

        AuditLogger::log('Created payment instruction', 'payment_instruction', $instruction->id, 'success', [
            'title' => $instruction->title,
        ]);

        return $instruction->fresh();
    }

    public function updateInstruction(string $id, array $data): PaymentInstruction
    {
        $instruction = $this->findOrFail($id);

        $payload = array_filter([
            'title' => array_key_exists('title', $data) ? (HtmlSanitizer::plainText($data['title']) ?? '') : null,
            'bank_name' => array_key_exists('bank_name', $data) ? HtmlSanitizer::plainText($data['bank_name']) : null,
            'account_number' => array_key_exists('account_number', $data) ? HtmlSanitizer::plainText($data['account_number']) : null,
            'account_holder' => array_key_exists('account_holder', $data) ? HtmlSanitizer::plainText($data['account_holder']) : null,
            'transfer_content' => array_key_exists('transfer_content', $data) ? HtmlSanitizer::plainText($data['transfer_content']) : null,
            'qr_code_image' => array_key_exists('qr_code_image', $data) ? $data['qr_code_image'] : null,
            'content' => array_key_exists('content', $data) ? HtmlSanitizer::richHtml($data['content']) : null,
            'status' => isset($data['status']) ? $this->normalizeStatus($data['status']) : null,
        ], fn ($value) => $value !== null);

        $instruction = $this->paymentInstructionRepository->update($instruction, $payload);

        if (($payload['status'] ?? $instruction->status) === PaymentInstruction::STATUS_ACTIVE) {
            $this->paymentInstructionRepository->deactivateOthers($instruction->id);
        }

        AuditLogger::log('Updated payment instruction', 'payment_instruction', $instruction->id, 'success', [
            'title' => $instruction->title,
        ]);

        return $instruction;
    }

    public function activateInstruction(string $id): PaymentInstruction
    {
        $instruction = $this->findOrFail($id);

        DB::transaction(function () use ($instruction) {
            $this->paymentInstructionRepository->deactivateOthers($instruction->id);
            $this->paymentInstructionRepository->update($instruction, ['status' => PaymentInstruction::STATUS_ACTIVE]);
        });

        AuditLogger::log('Activated payment instruction', 'payment_instruction', $instruction->id, 'success', [
            'title' => $instruction->title,
        ]);

        return $instruction->fresh();
    }

    public function deleteInstruction(string $id): bool
    {
        $instruction = $this->findOrFail($id);
        $title = $instruction->title;
        $deleted = $this->paymentInstructionRepository->delete($instruction);

        if ($deleted) {
            AuditLogger::log('Deleted payment instruction', 'payment_instruction', $id, 'success', [
                'title' => $title,
            ]);
        }

        return $deleted;
    }

    protected function normalizeStatus(string $status): string
    {
        $value = strtolower(trim($status));

        return $value === PaymentInstruction::STATUS_ACTIVE
            ? PaymentInstruction::STATUS_ACTIVE
            : PaymentInstruction::STATUS_INACTIVE;
    }
}
