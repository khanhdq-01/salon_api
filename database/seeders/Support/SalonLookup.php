<?php

namespace Database\Seeders\Support;

use App\Models\Role;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceStyleOption;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;

final class SalonLookup
{
    /** @var Collection<int, Salon>|null */
    private static ?Collection $salons = null;

    /** @var array<string, User> */
    private static array $usersByEmail = [];

    /** @var Collection<int, User>|null */
    private static ?Collection $customerPool = null;

    public static function salons(): Collection
    {
        if (self::$salons === null) {
            self::$salons = Salon::query()->orderBy('created_at')->get();
        }

        return self::$salons;
    }

    public static function salonAt(int $index): Salon
    {
        $salon = self::salons()->get($index);

        if (! $salon) {
            throw new \RuntimeException("Salon at index {$index} not found.");
        }

        return $salon;
    }

    public static function userByEmail(string $email): User
    {
        if (! isset(self::$usersByEmail[$email])) {
            $user = User::query()->where('email', $email)->first();

            if (! $user) {
                throw new \RuntimeException("User with email {$email} not found.");
            }

            self::$usersByEmail[$email] = $user;
        }

        return self::$usersByEmail[$email];
    }

    public static function customerByEmail(string $email): User
    {
        $user = User::query()
            ->where('email', $email)
            ->where('role_id', Role::ID_CUSTOMER)
            ->first();

        if ($user) {
            self::$usersByEmail[$email] = $user;

            return $user;
        }

        if (self::$customerPool === null) {
            self::$customerPool = User::query()
                ->where('role_id', Role::ID_CUSTOMER)
                ->orderBy('email')
                ->get();
        }

        if (self::$customerPool->isEmpty()) {
            throw new \RuntimeException('No demo customers found.');
        }

        $index = abs(crc32($email)) % self::$customerPool->count();

        return self::$customerPool[$index];
    }

    public static function staffByName(string $salonId, string $name): Staff
    {
        $staff = Staff::query()
            ->where('salon_id', $salonId)
            ->where('name', $name)
            ->first();

        if (! $staff) {
            throw new \RuntimeException("Staff '{$name}' not found in salon {$salonId}.");
        }

        return $staff;
    }

    public static function serviceByName(string $salonId, string $name): Service
    {
        $service = Service::query()
            ->where('salon_id', $salonId)
            ->where('name', $name)
            ->first();

        if (! $service) {
            throw new \RuntimeException("Service '{$name}' not found in salon {$salonId}.");
        }

        return $service;
    }

    public static function styleByName(string $serviceId, string $name): ?ServiceStyleOption
    {
        return ServiceStyleOption::query()
            ->where('service_id', $serviceId)
            ->where('name', $name)
            ->first();
    }

    public static function reset(): void
    {
        self::$salons = null;
        self::$usersByEmail = [];
        self::$customerPool = null;
    }
}
