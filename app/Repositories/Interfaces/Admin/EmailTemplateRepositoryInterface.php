<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EmailTemplateRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(string $id): ?EmailTemplate;

    public function update(EmailTemplate $template, array $data): EmailTemplate;
}
