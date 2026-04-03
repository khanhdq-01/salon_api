<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\EmailTemplate;

interface EmailTemplateRepositoryInterface
{
    public function findActiveByKey(string $templateKey): ?EmailTemplate;
}
