<?php

namespace App\Http\Requests\Api\V1\Owner;

use Illuminate\Foundation\Http\FormRequest;

class BroadcastNotificationRequest extends FormRequest
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
            'type' => ['sometimes', 'string', \Illuminate\Validation\Rule::in(\App\Support\NotificationTypes::values())],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ];
    }
}
