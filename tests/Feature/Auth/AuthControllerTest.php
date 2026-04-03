<?php

namespace Tests\Feature\Auth;

use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DemoRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoRolesSeeder::class);
    }

    // -------------------------------------------------------------------------
    // POST /api/auth/register
    // -------------------------------------------------------------------------

    public function test_register_returns_201_with_user_and_verification_required(): void
    {
        $user = User::query()->create([
            'id' => (string) Str::uuid(),
            'role_id' => Role::ID_CUSTOMER,
            'name' => 'John Doe',
            'email' => 'registered@example.com',
            'password' => Hash::make('Secret@123'),
            'status' => User::STATUS_PENDING,
            'email_verified_at' => null,
        ]);

        $this->mock(AuthServiceInterface::class, function ($mock) use ($user) {
            $mock->shouldReceive('register')
                ->once()
                ->andReturn(['user' => $user]);
        });

        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'Secret@123',
            'password_confirmation' => 'Secret@123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['user', 'verification_required']])
            ->assertJsonPath('data.verification_required', true);
    }

    public function test_register_returns_422_on_validation_failure(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'  => '',
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors']);
    }

    public function test_register_returns_422_when_password_not_confirmed(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'Secret@123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422);
    }

    // -------------------------------------------------------------------------
    // POST /api/auth/login
    // -------------------------------------------------------------------------

    public function test_login_returns_token_on_valid_credentials(): void
    {
        $this->mock(AuthServiceInterface::class, function ($mock) {
            $mock->shouldReceive('login')
                ->once()
                ->andReturn('valid.jwt.token');
        });

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'john@example.com',
            'password' => 'Secret@123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']])
            ->assertJsonPath('data.token', 'valid.jwt.token');
    }

    public function test_login_returns_401_on_invalid_credentials(): void
    {
        $this->mock(AuthServiceInterface::class, function ($mock) {
            $mock->shouldReceive('login')
                ->once()
                ->andThrow(new \Exception('Unauthorized'));
        });

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_login_returns_422_on_validation_failure(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'not-email',
        ]);

        $response->assertStatus(422);
    }

    // -------------------------------------------------------------------------
    // POST /api/auth/reset-password
    // -------------------------------------------------------------------------

    public function test_reset_password_returns_success_message(): void
    {
        $this->mock(AuthServiceInterface::class, function ($mock) {
            $mock->shouldReceive('resetPassword')
                ->once()
                ->with('john@example.com', 'reset-token', 'NewPass@123');
        });

        $response = $this->postJson('/api/auth/reset-password', [
            'email'                 => 'john@example.com',
            'token'                 => 'reset-token',
            'password'              => 'NewPass@123',
            'password_confirmation' => 'NewPass@123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Đặt lại mật khẩu thành công']);
    }

    public function test_reset_password_returns_422_when_user_not_found(): void
    {
        $this->mock(AuthServiceInterface::class, function ($mock) {
            $mock->shouldReceive('resetPassword')
                ->once()
                ->andThrow(new \App\Exceptions\BusinessException('Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'));
        });

        $response = $this->postJson('/api/auth/reset-password', [
            'email'                     => 'ghost@example.com',
            'token'                     => 'invalid-token',
            'password'                  => 'NewPass@123',
            'password_confirmation'     => 'NewPass@123',
        ]);

        $response->assertStatus(422);
    }

    public function test_forgot_password_returns_success_message(): void
    {
        $this->mock(AuthServiceInterface::class, function ($mock) {
            $mock->shouldReceive('forgotPassword')
                ->once()
                ->with('john@example.com');
        });

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Nếu Email tồn tại trong hệ thống, chúng tôi đã gửi Email hướng dẫn.']);
    }
}
