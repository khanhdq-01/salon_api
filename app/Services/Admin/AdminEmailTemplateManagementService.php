<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminEmailTemplateManagementServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\EmailTemplate;
use App\Repositories\Interfaces\Admin\EmailTemplateRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\HtmlSanitizer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminEmailTemplateManagementService implements AdminEmailTemplateManagementServiceInterface
{
    public function __construct(
        protected EmailTemplateRepositoryInterface $emailTemplateRepository
    ) {}

    public function listTemplates(array $filters): LengthAwarePaginator
    {
        if (! empty($filters['status'])) {
            $filters['status'] = $this->normalizeStatus($filters['status']);
        }

        return $this->emailTemplateRepository->paginate($filters);
    }

    public function findOrFail(string $id): EmailTemplate
    {
        $template = $this->emailTemplateRepository->findById($id);

        if (! $template) {
            throw new BusinessException('Email template không tồn tại.', 'EMAIL_TEMPLATE_NOT_FOUND', 404);
        }

        return $template;
    }

    public function updateTemplate(string $id, array $data): EmailTemplate
    {
        $template = $this->findOrFail($id);

        $payload = [];

        if (array_key_exists('template_name', $data)) {
            $payload['template_name'] = HtmlSanitizer::plainText($data['template_name']) ?? '';
        }

        if (array_key_exists('subject', $data)) {
            $payload['subject'] = HtmlSanitizer::plainText($data['subject']) ?? '';
        }

        if (array_key_exists('content', $data)) {
            $payload['content'] = HtmlSanitizer::richHtml($data['content']);
        }

        if (array_key_exists('status', $data)) {
            $payload['status'] = $this->normalizeStatus($data['status']);
        }

        $template = $this->emailTemplateRepository->update($template, $payload);

        AuditLogger::log('Updated email template', 'email_template', $template->id, 'success', [
            'template_key' => $template->template_key,
        ]);

        return $template;
    }

    protected function normalizeStatus(string $status): string
    {
        return strtolower($status) === EmailTemplate::STATUS_ACTIVE
            ? EmailTemplate::STATUS_ACTIVE
            : EmailTemplate::STATUS_INACTIVE;
    }
}
