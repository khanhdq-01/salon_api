<?php

namespace App\Repositories\Eloquent\Customer;

use App\Models\EmailTemplate;
use App\Repositories\Interfaces\Customer\EmailTemplateRepositoryInterface;

class EmailTemplateRepository implements EmailTemplateRepositoryInterface
{
    public function __construct(
        protected EmailTemplate $model
    ) {}

    public function findActiveByKey(string $templateKey): ?EmailTemplate
    {
        return $this->model->newQuery()
            ->where('template_key', $templateKey)
            ->active()
            ->first();
    }
}
