<?php

namespace Tests\Unit\Admin;

use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use App\Services\Admin\AdminSubscriptionManagementService;
use App\Services\Owner\SubscriptionApprovalEmailService;
use Carbon\Carbon;
use Mockery;
use Mockery\MockInterface;
use ReflectionMethod;
use Tests\TestCase;

class AdminSubscriptionManagementServiceTest extends TestCase
{
    private SubscriptionRepositoryInterface&MockInterface $subscriptionRepository;
    private UserRepositoryInterface&MockInterface $userRepository;
    private PackageRepositoryInterface&MockInterface $packageRepository;
    private SubscriptionApprovalEmailService&MockInterface $approvalEmailService;
    private AdminSubscriptionManagementService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriptionRepository = Mockery::mock(SubscriptionRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->packageRepository = Mockery::mock(PackageRepositoryInterface::class);
        $this->approvalEmailService = Mockery::mock(SubscriptionApprovalEmailService::class);

        $this->service = new AdminSubscriptionManagementService(
            $this->approvalEmailService,
            $this->subscriptionRepository,
            $this->userRepository,
            $this->packageRepository,
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_resolve_status_after_rejection_restores_active_when_end_date_still_valid(): void
    {
        $subscription = new Subscription([
            'approved_at' => now()->subMonth(),
            'end_date' => Carbon::today()->addDays(10),
        ]);

        $status = $this->invokeResolveStatusAfterRejection($subscription);

        $this->assertSame(Subscription::STATUS_ACTIVE, $status);
    }

    public function test_resolve_status_after_rejection_restores_expired_when_end_date_passed(): void
    {
        $subscription = new Subscription([
            'approved_at' => now()->subMonths(2),
            'end_date' => Carbon::today()->subDay(),
        ]);

        $status = $this->invokeResolveStatusAfterRejection($subscription);

        $this->assertSame(Subscription::STATUS_EXPIRED, $status);
    }

    public function test_resolve_status_after_rejection_keeps_rejected_for_initial_payment(): void
    {
        $subscription = new Subscription([
            'approved_at' => null,
            'end_date' => null,
        ]);

        $status = $this->invokeResolveStatusAfterRejection($subscription);

        $this->assertSame(Subscription::STATUS_REJECTED, $status);
    }

    public function test_resolve_status_after_rejection_returns_expired_when_no_end_date_after_prior_activation(): void
    {
        $subscription = new Subscription([
            'approved_at' => now()->subMonth(),
            'end_date' => null,
        ]);

        $status = $this->invokeResolveStatusAfterRejection($subscription);

        $this->assertSame(Subscription::STATUS_EXPIRED, $status);
    }

    public function test_reject_upgrade_restores_active_subscription_and_clears_upgrade_fields(): void
    {

        $admin = new User(['id' => 'admin-1']);
        $subscription = Mockery::mock(Subscription::class)->makePartial();
        $subscription->id = 'sub-1';
        $subscription->status = Subscription::STATUS_AWAITING_PAYMENT;
        $subscription->approved_at = now()->subMonth();
        $subscription->end_date = Carbon::today()->addDays(15);
        $subscription->package_id = 'pkg-current';
        $subscription->requested_package_id = 'pkg-standard';
        $subscription->requested_amount = 500000;
        $subscription->requested_at = now();
        $subscription->payment_proof = 'proof.png';
        $subscription->payment_note = 'note';

        $this->subscriptionRepository
            ->shouldReceive('findById')
            ->once()
            ->with('sub-1')
            ->andReturn($subscription);

        $this->subscriptionRepository
            ->shouldReceive('update')
            ->once()
            ->with($subscription, Mockery::on(function (array $payload) use ($admin) {
                return $payload['status'] === Subscription::STATUS_ACTIVE
                    && ! array_key_exists('requested_package_id', $payload)
                    && ! array_key_exists('requested_amount', $payload)
                    && ! array_key_exists('requested_at', $payload)
                    && $payload['payment_proof'] === null
                    && $payload['payment_note'] === null
                    && $payload['reviewed_by'] === $admin->id;
            }))
            ->andReturn($subscription);

        $this->subscriptionRepository
            ->shouldReceive('subscriptionRelations')
            ->once()
            ->andReturn(['package', 'requestedPackage']);

        $subscription->shouldReceive('fresh')
            ->once()
            ->with(['package', 'requestedPackage'])
            ->andReturnSelf();

        $result = $this->service->rejectUpgrade('sub-1', $admin);

        $this->assertSame($subscription, $result);
    }

    public function test_reject_upgrade_restores_expired_when_current_package_already_expired(): void
    {

        $admin = new User(['id' => 'admin-1']);
        $subscription = Mockery::mock(Subscription::class)->makePartial();
        $subscription->id = 'sub-2';
        $subscription->status = Subscription::STATUS_AWAITING_PAYMENT;
        $subscription->approved_at = now()->subMonths(2);
        $subscription->end_date = Carbon::today()->subDays(3);
        $subscription->requested_package_id = 'pkg-standard';
        $subscription->payment_proof = 'proof.png';

        $this->subscriptionRepository
            ->shouldReceive('findById')
            ->once()
            ->with('sub-2')
            ->andReturn($subscription);

        $this->subscriptionRepository
            ->shouldReceive('update')
            ->once()
            ->with($subscription, Mockery::on(function (array $payload) {
                return $payload['status'] === Subscription::STATUS_EXPIRED
                    && ! array_key_exists('requested_package_id', $payload);
            }))
            ->andReturn($subscription);

        $this->subscriptionRepository
            ->shouldReceive('subscriptionRelations')
            ->once()
            ->andReturn([]);

        $subscription->shouldReceive('fresh')->once()->andReturnSelf();

        $result = $this->service->rejectUpgrade('sub-2', $admin);

        $this->assertSame($subscription, $result);
    }

    private function invokeResolveStatusAfterRejection(Subscription $subscription): string
    {
        $method = new ReflectionMethod(AdminSubscriptionManagementService::class, 'resolveStatusAfterRejection');
        $method->setAccessible(true);

        return $method->invoke($this->service, $subscription);
    }
}
