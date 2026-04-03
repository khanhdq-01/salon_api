<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->syncSeederImageAssets();

        DB::transaction(function (): void {
            $this->call([
                DemoRolesSeeder::class,
                PackageSeeder::class,
                AdminSeeder::class,
                OwnerSeeder::class,
                CustomerSeeder::class,
                SalonSeeder::class,
                SalonImageSeeder::class,
                ServiceSeeder::class,
                ServiceStyleSeeder::class,
                HairstyleArticleSeeder::class,
                StaffSeeder::class,
                // WorkingScheduleSeeder::class, // Seeder generate work schedule for July-August in StaffSeeder
                BookingSeeder::class,
                PaymentSeeder::class,
                ReviewSeeder::class,
                NotificationSeeder::class,
                FavoriteSalonSeeder::class,
                FavoriteProductSeeder::class,
                AuditLogSeeder::class,
            ]);
        });

        $this->command?->info('Demo data seeded: 30 salons, 1 admin, 1 primary owner (owner@gmail.com), 20 customers, 6 staff on primary salon.');
        $this->command?->info('Primary salon: Luxury Hair Studio Hà Đông — ~100 generated bookings with payments and schedules.');
        $this->command?->info('Password for all accounts: 123456');
    }

    private function syncSeederImageAssets(): void
    {
        $this->syncImageDirectory(
            database_path('seeders/img-salon'),
            storage_path('app/public/img-salon'),
            'img-salon'
        );

        $this->syncImageDirectory(
            database_path('seeders/img-hair'),
            storage_path('app/public/img-hair'),
            'img-hair'
        );

        $this->syncImageDirectory(
            database_path('seeders/img-articles'),
            storage_path('app/public/img-articles'),
            'img-articles'
        );

        $this->syncImageDirectory(
            database_path('seeders/avt-customer'),
            storage_path('app/public/avt-customer'),
            'avt-customer'
        );
    }

    private function syncImageDirectory(string $sourceDirectory, string $targetDirectory, string $label): void
    {
        if (! File::isDirectory($sourceDirectory)) {
            $this->command?->warn("Seed image source not found: {$sourceDirectory}");

            return;
        }

        File::ensureDirectoryExists($targetDirectory);

        foreach (File::allFiles($sourceDirectory) as $file) {
            $sourcePath = $file->getPathname();
            $relativePath = ltrim(str_replace($sourceDirectory, '', $sourcePath), DIRECTORY_SEPARATOR."/\\");
            $targetPath = $targetDirectory.DIRECTORY_SEPARATOR.$relativePath;

            File::ensureDirectoryExists(dirname($targetPath));
            File::copy($sourcePath, $targetPath);
        }

        $this->command?->info("Synced {$label} seed images to storage/app/public/{$label}");
    }
}
