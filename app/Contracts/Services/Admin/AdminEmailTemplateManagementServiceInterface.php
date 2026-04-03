<?php

namespace App\Contracts\Services\Admin;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminEmailTemplateManagementServiceInterface
{
    public function listTemplates(array $filters): LengthAwarePaginator;

    public function findOrFail(string $id): EmailTemplate;

    public function updateTemplate(string $id, array $data): EmailTemplate;
}
