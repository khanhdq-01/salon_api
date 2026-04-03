<?php

namespace App\Http\Resources\Api\V1\Customer;

use App\Support\NotificationTypes;
use App\Support\SalonImageResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'salon_name' => $this->salon?->name,
            'salon_image_url' => SalonImageResolver::resolve($this->salon),
            'type' => $this->type ?? NotificationTypes::GENERAL,
            'type_label' => NotificationTypes::label($this->type ?? NotificationTypes::GENERAL),
            'title' => $this->title,
            'content' => $this->content,
            'read_at' => $this->read_at?->toIso8601String(),
            'created_at' => ($this->sent_at ?? $this->created_at)?->toIso8601String(),
        ];
    }
}
