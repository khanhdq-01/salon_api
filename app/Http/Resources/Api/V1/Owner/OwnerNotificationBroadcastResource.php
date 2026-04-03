<?php

namespace App\Http\Resources\Api\V1\Owner;

use App\Support\NotificationTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerNotificationBroadcastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'type' => $this->type ?? NotificationTypes::GENERAL,
            'type_label' => NotificationTypes::label($this->type ?? NotificationTypes::GENERAL),
            'title' => $this->title,
            'content' => $this->content,
            'recipient_count' => $this->recipient_count,
            'scheduled_at' => $this->formatLocalDateTime($this->scheduled_at),
            'sent_at' => $this->formatLocalDateTime($this->sent_at),
            'status' => $this->sent_at ? 'sent' : 'scheduled',
            'created_at' => $this->formatLocalDateTime($this->created_at),
        ];
    }

    protected function formatLocalDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value
            ->timezone(config('app.timezone', 'Asia/Ho_Chi_Minh'))
            ->format('Y-m-d\TH:i:s');
    }
}
