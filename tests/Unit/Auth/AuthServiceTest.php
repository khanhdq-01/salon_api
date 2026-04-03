<?php

namespace Tests\Unit\Auth;

use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Contracts\Services\Customer\EmailVerificationServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\User;
use Database\Seeders\DemoRolesSeeder;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;
use App\Services\Customer\AuthService;
use App\Support\AuthEmailContentBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserRepositoryInterface&MockInterface $userRepository;

    private EmailVerificationServiceInterface&MockInterface $emailVerificationService;

    private AdminSettingsServiceInterface&MockInterface $settingsService;

    private AuthEmailContentBuilder&MockInterface $emailContentBuilder;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->emailVerificationService = Mockery::mock(EmailVerificationServiceInterface::class);
        $this->settingsService = Mockery::mock(AdminSettingsServiceInterface::class);
        $this->emailContentBuilder = Mockery::mock(AuthEmailContentBuilder::class);

        $this->authService = new AuthService(
            $this->userRepository,
            $this->emailVerificationService,
            $this->settingsService,
            $this->emailContentBuilder,
        );

        $this->seed(DemoRolesSeeder::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register_creates_pending_user_and_sends_verification(): void
    {
        $inputData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123!',
        ];

        $mockUser = Mockery::mock(User::class)->makePartial();
        $mockUser->shouldReceive('load')->once()->with('role')->andReturnSelf();

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->withArgs(function (array $data) use ($inputData) {
                return $data['email'] === $inputData['email']
                    && $data['role_id'] === 4
                    && $data['status'] === User::STATUS_PENDING
                    && $data['email_verified_at'] === null
                    && isset($data['id'])
                    && Hash::check($inputData['password'], $data['password']);
            })
            ->andReturn($mockUser);

        $this->emailVerificationService
            ->shouldReceive('registerAndSendVerification')
            ->once()
            ->with($mockUser);

        $result = $this->authService->register($inputData);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayNotHasKey('token', $result);
    }

    public function test_login_returns_token_on_valid_credentials(): void
    {
        $credentials = ['email' => 'john@example.com', 'password' => 'secret123'];
        $user = Mockery::mock(User::class);
        $user->shouldReceive('isCustomer')->andReturn(true);
        $user->shouldReceive('hasVerifiedEmail')->andReturn(true);

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('valid.jwt.token');

        Auth::shouldReceive('user')->once()->andReturn($user);

        $token = $this->authService->login($credentials);

        $this->assertEquals('valid.jwt.token', $token);
    }

    public function test_login_blocks_unverified_customer(): void
    {
        $credentials = ['email' => 'john@example.com', 'password' => 'secret123'];
        $user = Mockery::mock(User::class);
        $user->shouldReceive('isCustomer')->andReturn(true);
        $user->shouldReceive('hasVerifiedEmail')->andReturn(false);

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('valid.jwt.token');

        Auth::shouldReceive('user')->once()->andReturn($user);
        Auth::shouldReceive('logout')->once();

        $this->expectException(BusinessException::class);

        $this->authService->login($credentials);
    }

    public function test_login_throws_exception_on_invalid_credentials(): void
    {
        $credentials = ['email' => 'wrong@example.com', 'password' => 'wrongpass'];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unauthorized');

        $this->authService->login($credentials);
    }

    public function test_change_password_delegates_to_repository(): void
    {
        $user = Mockery::mock(User::class);
        $newPass = 'newSecurePass123';

        $this->userRepository
            ->shouldReceive('updatePassword')
            ->once()
            ->with($user, $newPass);

        $this->authService->changePassword($user, $newPass);
    }

    public function test_reset_password_updates_when_token_valid(): void
    {
        Bus::fake();

        $user = User::query()->create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'role_id' => 4,
            'name' => 'John Doe',
            'email' => 'reset-test@example.com',
            'password' => Hash::make('Password123!'),
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        $email = $user->email;
        $token = 'valid-token';
        $newPass = 'ResetPass456!';

        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with($email)
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('updatePassword')
            ->once()
            ->with($user, $newPass);

        Password::shouldReceive('reset')
            ->once()
            ->andReturnUsing(function ($credentials, $callback) use ($user, $newPass) {
                $callback($user, $newPass);

                return Password::PASSWORD_RESET;
            });

        $this->authService->resetPassword($email, $token, $newPass);

        $this->assertTrue(true);
    }

    public function test_forgot_password_queues_email_for_active_customer(): void
    {
        Bus::fake();

        $user = User::query()->create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'role_id' => 4,
            'name' => 'Jane Doe',
            'email' => 'forgot-test@example.com',
            'password' => Hash::make('Password123!'),
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);

        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with($user->email)
            ->andReturn($user);

        $this->settingsService
            ->shouldReceive('getSettings')
            ->once()
            ->andReturn(['enable_notifications' => true]);

        Password::shouldReceive('createToken')
            ->once()
            ->with($user)
            ->andReturn('reset-token');

        $this->emailContentBuilder
            ->shouldReceive('resetPasswordUrl')
            ->once()
            ->andReturn('https://app.test/reset-password?token=reset-token');

        $this->emailContentBuilder
            ->shouldReceive('branding')
            ->once()
            ->andReturn([
                'system_name' => 'Salonify',
                'logo_url' => null,
                'from_address' => 'noreply@test.com',
                'from_name' => 'Salonify',
            ]);

        $this->authService->forgotPassword($user->email);

        Bus::assertDispatched(\App\Jobs\SendResetPasswordJob::class);
    }

    public function test_forgot_password_does_nothing_when_user_not_found(): void
    {
        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with('notfound@example.com')
            ->andReturn(null);

        $this->authService->forgotPassword('notfound@example.com');

        $this->assertTrue(true);
    }

    public function test_logout_calls_auth_logout(): void
    {
        Auth::shouldReceive('logout')->once();

        $this->authService->logout();
    }

    public function test_logout_all_devices_increments_token_version(): void
    {
        $userMock = Mockery::mock(User::class)->makePartial();
        $userMock->token_version = 1;
        $userMock->shouldReceive('save')->once();

        $this->authService->logoutAllDevices($userMock);

        $this->assertEquals(2, $userMock->token_version);
    }
}
