<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Support\NotificationTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'content' => ['sometimes', 'required', 'string', 'max:50000'],
            'type' => ['sometimes', 'required', 'string', Rule::in(NotificationTypes::values())],
            'scheduled_at' => ['sometimes', 'required', 'date', 'after:now'],
        ];
    }
}
