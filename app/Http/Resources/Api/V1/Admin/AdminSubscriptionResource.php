<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Models\Subscription;
use App\Support\SubscriptionExpiry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'package_id' => $this->package_id,
            'requested_package_id' => $this->requested_package_id,
            'user' => $this->whenLoaded('owner', fn () => $this->owner->name),
            'owner_email' => $this->whenLoaded('owner', fn () => $this->owner->email),
            'owner_phone' => $this->whenLoaded('owner', fn () => $this->owner->phone),
            'salons' => $this->resolveSalonDetails(),
            'package' => $this->resolvePackageLabel(),
            'current_package' => $this->whenLoaded('package', fn () => $this->package?->name),
            'current_package_detail' => $this->when(
                $this->relationLoaded('package') && $this->package,
                fn () => new AdminPackageResource($this->package),
            ),
            'requested_package' => $this->whenLoaded('requestedPackage', fn () => $this->requestedPackage?->name),
            'requested_package_detail' => $this->when(
                $this->relationLoaded('requestedPackage') && $this->requestedPackage,
                fn () => new AdminPackageResource($this->requestedPackage),
            ),
            'requested_package_request_status' => $this->resolveRequestedPackageRequestStatus(),
            'requested_package_request_status_label' => $this->resolveRequestedPackageRequestStatusLabel(),
            'salon' => $this->resolveSalonNames(),
            'status' => $this->formatStatusLabel(),
            'status_key' => $this->status,
            'can_review' => $this->canAdminReviewPayment(),
            'requested_amount' => $this->requested_amount,
            'approved_amount' => $this->approved_amount,
            'requested_at' => $this->formatDateTime($this->requested_at),
            'payment_proof' => $this->payment_proof,
            'payment_proof_url' => $this->resolvePaymentProofUrl($this->payment_proof),
            'payment_note' => $this->payment_note,
            'approved_at' => $this->formatDateTime($this->approved_at ?? $this->reviewed_at),
            'start_date' => $this->formatDate($this->start_date),
            'end_date' => $this->formatDate($this->end_date),
            'auto_renew' => (bool) $this->auto_renew,
            'reviewed_at' => $this->formatDateTime($this->reviewed_at),
            'created_at' => $this->formatDateTime($this->created_at),
        ];
    }

    private function resolveRequestedPackageRequestStatus(): ?string
    {
        if (! $this->requested_package_id) {
            return null;
        }

        if ($this->isAwaitingPayment()) {
            return 'pending';
        }

        if ($this->reviewed_at) {
            return 'rejected';
        }

        return null;
    }

    private function resolveRequestedPackageRequestStatusLabel(): ?string
    {
        return match ($this->resolveRequestedPackageRequestStatus()) {
            'pending' => 'Chờ duyệt',
            'rejected' => 'Từ chối',
            default => null,
        };
    }

    private function canAdminReviewPayment(): bool
    {
        if (! $this->isAwaitingPayment()) {
            return false;
        }

        if ($this->requested_package_id) {
            return true;
        }

        return (bool) ($this->payment_proof || $this->requested_at);
    }

    private function resolvePackageLabel(): ?string
    {
        $current = $this->relationLoaded('package') ? $this->package?->name : null;
        $requested = $this->relationLoaded('requestedPackage') ? $this->requestedPackage?->name : null;

        if ($this->isAwaitingPayment() && $current && $requested) {
            if ($current === $requested) {
                return "{$current} (Gia hạn)";
            }

            return "{$current} → {$requested}";
        }

        return $current ?? $requested;
    }

    private function formatStatusLabel(): string
    {
        if ($this->isAwaitingPayment()) {
            return $this->status === Subscription::STATUS_PENDING_APPROVAL
                ? 'Pending Approval'
                : 'Awaiting Payment';
        }

        $status = SubscriptionExpiry::resolveEffectiveStatus($this->resource);

        return match ($status) {
            Subscription::STATUS_PENDING_APPROVAL => 'Pending Approval',
            Subscription::STATUS_AWAITING_PAYMENT => 'Awaiting Payment',
            Subscription::STATUS_APPROVED => 'Approved',
            Subscription::STATUS_REJECTED => 'Rejected',
            Subscription::STATUS_ACTIVE => 'Active',
            Subscription::STATUS_EXPIRED => 'Expired',
            Subscription::STATUS_CANCELLED => 'Canceled',
            default => ucfirst((string) $this->status),
        };
    }

    private function resolveSalonNames(): ?string
    {
        if (! $this->relationLoaded('owner') || ! $this->owner?->relationLoaded('ownedSalons')) {
            return null;
        }

        $names = $this->owner->ownedSalons
            ->pluck('name')
            ->filter()
            ->values();

        return $names->isEmpty() ? null : $names->implode(', ');
    }

    private function resolveSalonDetails(): mixed
    {
        if (! $this->relationLoaded('owner') || ! $this->owner?->relationLoaded('ownedSalons')) {
            return [];
        }

        return $this->owner->ownedSalons
            ->map(fn ($salon) => [
                'id' => $salon->id,
                'name' => $salon->name,
                'address' => $salon->address,
                'phone' => $salon->phone,
                'approval_status' => $salon->approval_status,
                'status' => $salon->status,
            ])
            ->values()
            ->all();
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return (string) $value;
    }

    private function resolvePaymentProofUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.$path;
    }
}
