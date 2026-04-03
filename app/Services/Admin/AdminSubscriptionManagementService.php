<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminSubscriptionManagementServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use App\Services\Owner\SubscriptionApprovalEmailService;
use App\Support\AuditLogger;
use App\Support\SubscriptionExpiry;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class AdminSubscriptionManagementService implements AdminSubscriptionManagementServiceInterface
{
    public function __construct(
        protected SubscriptionApprovalEmailService $approvalEmailService,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected UserRepositoryInterface $userRepository,
        protected PackageRepositoryInterface $packageRepository,
    ) {}

    public function listSubscriptions(array $filters): LengthAwarePaginator
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        if (! empty($filters['status'])) {
            $filters['status'] = $this->normalizeStatus($filters['status']);
        }

        return $this->subscriptionRepository->paginate($filters, $this->subscriptionRepository->subscriptionRelations());
    }

    public function createSubscription(array $data): Subscription
    {
        $this->assertOwner($data['owner_id']);

        $package = $this->packageRepository->findById($data['package_id']);
        $status = $this->normalizeStatus($data['status'] ?? Subscription::STATUS_ACTIVE);
        $approvedAt = $status === Subscription::STATUS_ACTIVE ? now() : null;
        $approvedAmount = $status === Subscription::STATUS_ACTIVE ? ($package?->price ?? 0) : null;

        $subscription = $this->subscriptionRepository->create([
            'owner_id' => $data['owner_id'],
            'package_id' => $data['package_id'],
            'status' => $status,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'auto_renew' => false,
            'approved_at' => $approvedAt,
            'approved_amount' => $approvedAmount,
        ]);

        AuditLogger::log('Created subscription', 'subscription', $subscription->id, 'success', [
            'owner_id' => $subscription->owner_id,
            'package_id' => $subscription->package_id,
        ]);

        return $subscription->load($this->subscriptionRepository->subscriptionRelations());
    }

    public function getSubscription(string $id): Subscription
    {
        return $this->findOrFail($id)->load($this->subscriptionRepository->subscriptionDetailRelations());
    }

    public function updateSubscription(string $id, array $data): Subscription
    {
        $subscription = $this->findOrFail($id);

        if (! empty($data['owner_id'])) {
            $this->assertOwner($data['owner_id']);
        }

        $payload = array_filter([
            'owner_id' => $data['owner_id'] ?? null,
            'package_id' => $data['package_id'] ?? null,
            'status' => isset($data['status']) ? $this->normalizeStatus($data['status']) : null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
        ], fn ($value) => $value !== null);

        $this->applyActiveStatusWhenStillValid($payload, $subscription);

        $this->subscriptionRepository->update($subscription, $payload);

        Cache::increment('salons:list:version');

        AuditLogger::log('Updated subscription', 'subscription', $subscription->id, 'success');

        return $subscription->fresh($this->subscriptionRepository->subscriptionRelations());
    }

    public function approveUpgrade(string $id, User $admin): Subscription
    {
        $subscription = $this->findOrFail($id);

        if (! $subscription->isAwaitingPayment()) {
            throw new BusinessException('Không có yêu cầu thanh toán đang chờ duyệt.', 'UPGRADE_REQUEST_NOT_FOUND', 422);
        }

        if ($subscription->requested_package_id) {
            return $this->approvePackageChange($subscription, $admin);
        }

        if ($subscription->payment_proof || $subscription->requested_at) {
            return $this->approveInitialPayment($subscription, $admin);
        }

        throw new BusinessException('Không có yêu cầu thanh toán đang chờ duyệt.', 'UPGRADE_REQUEST_NOT_FOUND', 422);
    }

    protected function approvePackageChange(Subscription $subscription, User $admin): Subscription
    {
        $package = $this->packageRepository->findActiveById($subscription->requested_package_id);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ yêu cầu không khả dụng.', 'PACKAGE_NOT_FOUND', 422);
        }

        $startDate = Carbon::today();
        $approvedAmount = $subscription->requested_amount ?? $package->price;

        $this->subscriptionRepository->update($subscription, [
            'package_id' => $package->id,
            'requested_package_id' => null,
            'requested_amount' => null,
            'requested_at' => null,
            'payment_proof' => null,
            'payment_note' => null,
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => $startDate->toDateString(),
            'end_date' => $package->calculateEndDate($startDate)->toDateString(),
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'approved_amount' => $approvedAmount,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ]);

        AuditLogger::log('Approved subscription upgrade', 'subscription', $subscription->id, 'success', [
            'admin_id' => $admin->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'approved_amount' => $approvedAmount,
        ]);

        $subscription = $subscription->fresh($this->subscriptionRepository->subscriptionRelations());

        Cache::increment('salons:list:version');

        $this->approvalEmailService->sendApprovalEmail($subscription);

        return $subscription;
    }

    protected function approveInitialPayment(Subscription $subscription, User $admin): Subscription
    {
        $package = $subscription->package ?? $this->packageRepository->findById($subscription->package_id);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không tồn tại.', 'PACKAGE_NOT_FOUND', 422);
        }

        $startDate = Carbon::today();
        $approvedAmount = $subscription->requested_amount ?? $package->price;

        $this->subscriptionRepository->update($subscription, [
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => $startDate->toDateString(),
            'end_date' => $package->calculateEndDate($startDate)->toDateString(),
            'payment_proof' => null,
            'payment_note' => null,
            'requested_at' => null,
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'approved_amount' => $approvedAmount,
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ]);

        AuditLogger::log('Approved initial subscription payment', 'subscription', $subscription->id, 'success', [
            'admin_id' => $admin->id,
            'package_id' => $package->id,
            'approved_amount' => $approvedAmount,
        ]);

        $subscription = $subscription->fresh($this->subscriptionRepository->subscriptionRelations());

        Cache::increment('salons:list:version');

        $this->approvalEmailService->sendApprovalEmail($subscription);

        return $subscription;
    }

    public function rejectUpgrade(string $id, User $admin): Subscription
    {
        $subscription = $this->findOrFail($id);

        if (! $subscription->isAwaitingPayment()) {
            throw new BusinessException('Không có yêu cầu thanh toán đang chờ duyệt.', 'UPGRADE_REQUEST_NOT_FOUND', 422);
        }

        if (! $subscription->requested_package_id && ! $subscription->payment_proof && ! $subscription->requested_at) {
            throw new BusinessException('Không có yêu cầu thanh toán đang chờ duyệt.', 'UPGRADE_REQUEST_NOT_FOUND', 422);
        }

        $requestedPackageId = $subscription->requested_package_id;
        $isUpgradeRejection = (bool) $subscription->approved_at;

        $payload = [
            'status' => $this->resolveStatusAfterRejection($subscription),
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'payment_proof' => null,
            'payment_note' => null,
        ];

        if (! $isUpgradeRejection) {
            $payload['requested_package_id'] = null;
            $payload['requested_amount'] = null;
            $payload['requested_at'] = null;
        }

        $this->subscriptionRepository->update($subscription, $payload);

        AuditLogger::log(
            $isUpgradeRejection ? 'Rejected subscription upgrade request' : 'Rejected subscription payment request',
            'subscription',
            $subscription->id,
            'success',
            [
                'admin_id' => $admin->id,
                'requested_package_id' => $requestedPackageId,
                'restored_status' => $payload['status'],
            ]
        );

        Cache::increment('salons:list:version');

        return $subscription->fresh($this->subscriptionRepository->subscriptionRelations());
    }

    public function deleteSubscription(string $id): bool
    {
        $subscription = $this->findOrFail($id);
        $deleted = $this->subscriptionRepository->delete($subscription);

        if ($deleted) {
            AuditLogger::log('Deleted subscription', 'subscription', $id, 'success');
        }

        return $deleted;
    }

    protected function resolveStatusAfterRejection(Subscription $subscription): string
    {
        if (! $subscription->approved_at) {
            return Subscription::STATUS_REJECTED;
        }

        if (! $subscription->end_date) {
            return Subscription::STATUS_EXPIRED;
        }

        return Carbon::parse($subscription->end_date)->startOfDay()->lt(Carbon::today())
            ? Subscription::STATUS_EXPIRED
            : Subscription::STATUS_ACTIVE;
    }

    protected function applyActiveStatusWhenStillValid(array &$payload, Subscription $subscription): void
    {
        $endDate = $payload['end_date'] ?? $subscription->end_date;

        if (! $endDate) {
            return;
        }

        if (Carbon::parse($endDate)->startOfDay()->lt(Carbon::today())) {
            return;
        }

        $requestedStatus = $payload['status'] ?? $subscription->status;

        if (in_array($requestedStatus, [
            Subscription::STATUS_CANCELLED,
            Subscription::STATUS_REJECTED,
            Subscription::STATUS_PENDING_APPROVAL,
            Subscription::STATUS_AWAITING_PAYMENT,
        ], true)) {
            return;
        }

        $payload['status'] = Subscription::STATUS_ACTIVE;
    }

    protected function normalizeStatus(string $status): string
    {
        $value = strtolower(trim(str_replace(['-', ' '], '_', $status)));

        return match ($value) {
            'pending_approval', 'pendingapproval' => Subscription::STATUS_PENDING_APPROVAL,
            'awaiting_payment', 'awaitingpayment' => Subscription::STATUS_AWAITING_PAYMENT,
            'approved' => Subscription::STATUS_APPROVED,
            'rejected' => Subscription::STATUS_REJECTED,
            'active' => Subscription::STATUS_ACTIVE,
            'expired' => Subscription::STATUS_EXPIRED,
            'canceled', 'cancelled' => Subscription::STATUS_CANCELLED,
            default => Subscription::STATUS_ACTIVE,
        };
    }

    protected function assertOwner(string $ownerId): void
    {
        $owner = $this->userRepository->findByIdWithRole($ownerId);

        if (! $owner || $owner->role?->name !== Role::OWNER) {
            throw new BusinessException('Owner không hợp lệ.', 'INVALID_OWNER', 422);
        }
    }

    protected function findOrFail(string $id): Subscription
    {
        $subscription = $this->subscriptionRepository->findById($id);

        if (! $subscription) {
            throw new BusinessException('Subscription không tồn tại.', 'SUBSCRIPTION_NOT_FOUND', 404);
        }

        return $subscription;
    }
}
