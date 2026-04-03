<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\EmailTemplate;

interface EmailTemplateRepositoryInterface
{
    public function findActiveByKey(string $templateKey): ?EmailTemplate;
}
