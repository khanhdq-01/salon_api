<?php

namespace App\Support;

use App\Exceptions\BusinessException;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class StaffAccountProvisioner
{
    /**
     * @param  array{
     *     email: string,
     *     password: string,
     *     phone?: string|null,
     *     name?: string|null,
     * }  $account
     */
    public static function createForStaff(Staff $staff, User $employer, array $account): User
    {
        if (! $employer->isOwner() && ! $employer->isAdmin()) {
            throw new BusinessException('Chỉ chủ salon hoặc quản trị viên mới có thể tạo tài khoản nhân viên.', 'FORBIDDEN', 403);
        }

        if ($staff->user_id) {
            throw new BusinessException('Nhân viên này đã có tài khoản đăng nhập.', 'STAFF_ACCOUNT_EXISTS');
        }

        $email = strtolower(trim($account['email'] ?? ''));
        if ($email === '') {
            throw new BusinessException('Email đăng nhập là bắt buộc.', 'EMAIL_REQUIRED');
        }

        if (User::query()->where('email', $email)->exists()) {
            throw new BusinessException('Email đã được sử dụng.', 'EMAIL_EXISTS');
        }

        return DB::transaction(function () use ($staff, $employer, $account, $email) {
            $ownerId = $employer->isOwner() ? $employer->id : self::resolveOwnerIdForSalon($staff->salon_id);

            $user = User::query()->create([
                'id' => Str::uuid()->toString(),
                'role_id' => Role::ID_STAFF,
                'owner_id' => $ownerId,
                'name' => $account['name'] ?? $staff->name,
                'email' => $email,
                'password' => Hash::make($account['password']),
                'phone' => $account['phone'] ?? null,
                'status' => User::STATUS_ACTIVE,
                'token_version' => 0,
            ]);

            $staff->update(['user_id' => $user->id]);

            return $user->load('role');
        });
    }

    public static function syncAccount(Staff $staff, User $employer, array $account): ?User
    {
        $email = isset($account['email']) ? strtolower(trim((string) $account['email'])) : null;
        $password = $account['password'] ?? null;
        $phone = $account['phone'] ?? null;

        if (! $email && ! $password && $phone === null) {
            return $staff->user;
        }

        if ($staff->user_id) {
            $user = $staff->user;
            if ($email && $email !== $user->email) {
                if (User::query()->where('email', $email)->where('id', '!=', $user->id)->exists()) {
                    throw new BusinessException('Email đã được sử dụng.', 'EMAIL_EXISTS');
                }
                $user->email = $email;
            }
            if ($password) {
                $user->password = Hash::make($password);
            }
            if ($phone !== null) {
                $user->phone = $phone;
            }
            if (isset($account['name']) && $account['name']) {
                $user->name = $account['name'];
            }
            $user->save();

            return $user->fresh('role');
        }

        if ($email && $password) {
            return self::createForStaff($staff, $employer, [
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'name' => $account['name'] ?? $staff->name,
            ]);
        }

        return null;
    }

    private static function resolveOwnerIdForSalon(string $salonId): string
    {
        $ownerId = DB::table('salons')->where('id', $salonId)->value('owner_id');

        if (! $ownerId) {
            throw new BusinessException('Không xác định được chủ salon.', 'OWNER_NOT_FOUND', 404);
        }

        return (string) $ownerId;
    }
}
