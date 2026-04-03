<?php

namespace App\Http\Requests\Api\V1\Owner;

use App\Support\NotificationTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOwnerNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string', 'max:50000'],
            'type' => ['required', 'string', Rule::in(NotificationTypes::values())],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ];
    }
}
