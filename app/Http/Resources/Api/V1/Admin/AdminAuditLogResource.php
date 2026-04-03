<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Support\AuditLogPresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $targetLabel = AuditLogPresenter::resolveObjectLabel($this->resource);
        $module = AuditLogPresenter::moduleKey($this->resource);

        return [
            'id' => $this->id,
            'user' => AuditLogPresenter::resolveActorName($this->resource),
            'user_role' => AuditLogPresenter::resolveRole($this->resource),
            'module' => $module,
            'module_label' => AuditLogPresenter::moduleLabel($module),
            'action' => $this->action,
            'action_label' => AuditLogPresenter::actionLabel(
                $this->action,
                AuditLogPresenter::normalizeActionCategory($this->action, $this->resource),
            ),
            'action_category' => AuditLogPresenter::normalizeActionCategory($this->action, $this->resource),
            'message' => AuditLogPresenter::message($this->resource),
            'target' => (string) ($targetLabel ?? '—'),
            'target_type' => ucfirst($this->target_type),
            'target_id' => $this->target_id,
            'type' => ucfirst($this->target_type),
            'status' => ucfirst($this->status),
            'date' => $this->formatDateTime($this->created_at),
            'details' => $this->details,
            'ip_address' => $this->ip_address,
        ];
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i');
        }

        return (string) $value;
    }
}
