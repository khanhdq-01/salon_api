<?php

namespace App\Providers;

use App\Contracts\Services\Admin\AdminAuditLogServiceInterface;
use App\Contracts\Services\Admin\AdminBookingManagementServiceInterface;
use App\Contracts\Services\Admin\AdminDashboardServiceInterface;
use App\Contracts\Services\Admin\AdminEmailTemplateManagementServiceInterface;
use App\Contracts\Services\Admin\AdminPackageManagementServiceInterface;
use App\Contracts\Services\Admin\AdminPaymentInstructionManagementServiceInterface;
use App\Contracts\Services\Admin\AdminReviewManagementServiceInterface;
use App\Contracts\Services\Admin\AdminRevenueAnalyticsServiceInterface;
use App\Contracts\Services\Admin\AdminSalonManagementServiceInterface;
use App\Contracts\Services\Admin\AdminServiceManagementServiceInterface;
use App\Contracts\Services\Admin\AdminSettingsServiceInterface;
use App\Contracts\Services\Admin\AdminStaffManagementServiceInterface;
use App\Contracts\Services\Admin\AdminSubscriptionManagementServiceInterface;
use App\Contracts\Services\Admin\AdminUserManagementServiceInterface;
use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Contracts\Services\Customer\EmailVerificationServiceInterface;
use App\Contracts\Services\Customer\BookingServiceInterface;
use App\Contracts\Services\Customer\PaymentServiceInterface;
use App\Contracts\Services\Customer\ProfileServiceInterface;
use App\Contracts\Services\Customer\ReviewServiceInterface;
use App\Contracts\Services\Owner\OwnerDashboardServiceInterface;
use App\Contracts\Services\Owner\OwnerPackageLimitServiceInterface;
use App\Contracts\Services\Owner\OwnerWorkScheduleServiceInterface;
use App\Contracts\Services\Owner\OwnerPaymentInstructionServiceInterface;
use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Contracts\Services\Owner\OwnerReportServiceInterface;
use App\Contracts\Services\Staff\StaffPortalServiceInterface;
use App\Contracts\Services\Owner\OwnerSubscriptionServiceInterface;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Contracts\Services\Owner\ServiceCatalogServiceInterface;
use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Repositories\Eloquent\Admin\AuditLogRepository;
use App\Repositories\Eloquent\Admin\BookingRepository as AdminBookingRepository;
use App\Repositories\Eloquent\Admin\EmailTemplateRepository as AdminEmailTemplateRepository;
use App\Repositories\Eloquent\Admin\PackageRepository as AdminPackageRepository;
use App\Repositories\Eloquent\Admin\PaymentInstructionRepository as AdminPaymentInstructionRepository;
use App\Repositories\Eloquent\Admin\ReviewReportRepository;
use App\Repositories\Eloquent\Admin\ReviewRepository as AdminReviewRepository;
use App\Repositories\Eloquent\Admin\RoleRepository;
use App\Repositories\Eloquent\Admin\SalonRepository as AdminSalonRepository;
use App\Repositories\Eloquent\Admin\SubscriptionRepository as AdminSubscriptionRepository;
use App\Repositories\Eloquent\Admin\SystemSettingRepository;
use App\Repositories\Eloquent\Admin\UserRepository as AdminUserRepository;
use App\Repositories\Eloquent\Customer\BookingRepository;
use App\Repositories\Eloquent\Customer\EmailTemplateRepository as CustomerEmailTemplateRepository;
use App\Repositories\Eloquent\Customer\FavoriteRepository;
use App\Repositories\Eloquent\Customer\SearchHistoryRepository;
use App\Repositories\Eloquent\Customer\NotificationRepository;
use App\Repositories\Eloquent\Customer\PaymentRepository;
use App\Repositories\Eloquent\Customer\ReviewRepository;
use App\Repositories\Eloquent\Customer\ServiceStyleOptionRepository as CustomerServiceStyleOptionRepository;
use App\Repositories\Eloquent\Customer\EmailVerificationTokenRepository;
use App\Repositories\Eloquent\Customer\UserRepository;
use App\Repositories\Eloquent\Owner\BookingRepository as OwnerBookingRepository;
use App\Repositories\Eloquent\Owner\EmailTemplateRepository as OwnerEmailTemplateRepository;
use App\Repositories\Eloquent\Owner\PackageRepository as OwnerPackageRepository;
use App\Repositories\Eloquent\Owner\PaymentInstructionRepository as OwnerPaymentInstructionRepository;
use App\Repositories\Eloquent\Owner\SalonRepository;
use App\Repositories\Eloquent\Owner\SalonSettingsRepository;
use App\Repositories\Eloquent\Owner\SeatRepository;
use App\Repositories\Eloquent\Owner\ServiceRepository;
use App\Repositories\Eloquent\Owner\ServiceStyleOptionRepository;
use App\Repositories\Eloquent\Owner\StaffRepository;
use App\Repositories\Eloquent\Owner\StaffScheduleRepository;
use App\Repositories\Eloquent\Owner\SubscriptionRepository as OwnerSubscriptionRepository;
use App\Repositories\Interfaces\Admin\AuditLogRepositoryInterface;
use App\Repositories\Interfaces\Admin\BookingRepositoryInterface as AdminBookingRepositoryInterface;
use App\Repositories\Interfaces\Admin\EmailTemplateRepositoryInterface as AdminEmailTemplateRepositoryInterface;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface as AdminPackageRepositoryInterface;
use App\Repositories\Interfaces\Admin\PaymentInstructionRepositoryInterface as AdminPaymentInstructionRepositoryInterface;
use App\Repositories\Interfaces\Admin\ReviewReportRepositoryInterface;
use App\Repositories\Interfaces\Admin\ReviewRepositoryInterface as AdminReviewRepositoryInterface;
use App\Repositories\Interfaces\Admin\RoleRepositoryInterface;
use App\Repositories\Interfaces\Admin\SalonRepositoryInterface as AdminSalonRepositoryInterface;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface as AdminSubscriptionRepositoryInterface;
use App\Repositories\Interfaces\Admin\SystemSettingRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface as AdminUserRepositoryInterface;
use App\Repositories\Interfaces\Customer\BookingRepositoryInterface;
use App\Repositories\Interfaces\Customer\EmailTemplateRepositoryInterface as CustomerEmailTemplateRepositoryInterface;
use App\Repositories\Interfaces\Customer\FavoriteRepositoryInterface;
use App\Repositories\Interfaces\Customer\SearchHistoryRepositoryInterface;
use App\Repositories\Interfaces\Customer\NotificationRepositoryInterface;
use App\Repositories\Interfaces\Customer\PaymentRepositoryInterface;
use App\Repositories\Interfaces\Customer\ReviewRepositoryInterface;
use App\Repositories\Interfaces\Customer\ServiceStyleOptionRepositoryInterface as CustomerServiceStyleOptionRepositoryInterface;
use App\Repositories\Interfaces\Customer\EmailVerificationTokenRepositoryInterface;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\EmailTemplateRepositoryInterface as OwnerEmailTemplateRepositoryInterface;
use App\Repositories\Interfaces\Owner\PackageRepositoryInterface as OwnerPackageRepositoryInterface;
use App\Repositories\Interfaces\Owner\PaymentInstructionRepositoryInterface as OwnerPaymentInstructionRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonSettingsRepositoryInterface;
use App\Repositories\Interfaces\Owner\SeatRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceStyleOptionRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface as OwnerSubscriptionRepositoryInterface;
use App\Services\Admin\AdminAuditLogService;
use App\Services\Admin\AdminBookingManagementService;
use App\Services\Admin\AdminDashboardService;
use App\Services\Admin\AdminEmailTemplateManagementService;
use App\Services\Admin\AdminPackageManagementService;
use App\Services\Admin\AdminPaymentInstructionManagementService;
use App\Services\Admin\AdminReviewManagementService;
use App\Services\Admin\AdminRevenueAnalyticsService;
use App\Services\Admin\AdminSalonManagementService;
use App\Services\Admin\AdminServiceManagementService;
use App\Services\Admin\AdminSettingsService;
use App\Services\Admin\AdminStaffManagementService;
use App\Services\Admin\AdminSubscriptionManagementService;
use App\Services\Admin\AdminUserManagementService;
use App\Services\Customer\AuthService;
use App\Services\Customer\EmailVerificationService;
use App\Services\Customer\BookingService;
use App\Services\Customer\PaymentService;
use App\Services\Customer\ProfileService;
use App\Services\Customer\ReviewService;
use App\Services\Owner\OwnerDashboardService;
use App\Services\Owner\OwnerPackageLimitService;
use App\Services\Owner\OwnerPaymentInstructionService;
use App\Services\Owner\OwnerReportService;
use App\Services\Owner\OwnerSalonSettingsService;
use App\Services\Owner\OwnerSubscriptionService;
use App\Services\Owner\SalonService;
use App\Services\Owner\ServiceCatalogService;
use App\Services\Owner\StaffService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $repositoryBindings = [
            // Customer repositories
            UserRepositoryInterface::class => UserRepository::class,
            EmailVerificationTokenRepositoryInterface::class => EmailVerificationTokenRepository::class,
            BookingRepositoryInterface::class => BookingRepository::class,
            PaymentRepositoryInterface::class => PaymentRepository::class,
            ReviewRepositoryInterface::class => ReviewRepository::class,
            NotificationRepositoryInterface::class => NotificationRepository::class,
            CustomerEmailTemplateRepositoryInterface::class => CustomerEmailTemplateRepository::class,
            CustomerServiceStyleOptionRepositoryInterface::class => CustomerServiceStyleOptionRepository::class,
            FavoriteRepositoryInterface::class => FavoriteRepository::class,
            SearchHistoryRepositoryInterface::class => SearchHistoryRepository::class,

            // Owner repositories
            SalonRepositoryInterface::class => SalonRepository::class,
            ServiceRepositoryInterface::class => ServiceRepository::class,
            StaffRepositoryInterface::class => StaffRepository::class,
            OwnerPackageRepositoryInterface::class => OwnerPackageRepository::class,
            OwnerSubscriptionRepositoryInterface::class => OwnerSubscriptionRepository::class,
            OwnerPaymentInstructionRepositoryInterface::class => OwnerPaymentInstructionRepository::class,
            OwnerEmailTemplateRepositoryInterface::class => OwnerEmailTemplateRepository::class,
            OwnerBookingRepositoryInterface::class => OwnerBookingRepository::class,
            ServiceStyleOptionRepositoryInterface::class => ServiceStyleOptionRepository::class,
            SeatRepositoryInterface::class => SeatRepository::class,
            StaffScheduleRepositoryInterface::class => StaffScheduleRepository::class,
            SalonSettingsRepositoryInterface::class => SalonSettingsRepository::class,

            // Admin repositories
            AuditLogRepositoryInterface::class => AuditLogRepository::class,
            AdminPackageRepositoryInterface::class => AdminPackageRepository::class,
            AdminEmailTemplateRepositoryInterface::class => AdminEmailTemplateRepository::class,
            AdminPaymentInstructionRepositoryInterface::class => AdminPaymentInstructionRepository::class,
            SystemSettingRepositoryInterface::class => SystemSettingRepository::class,
            AdminSubscriptionRepositoryInterface::class => AdminSubscriptionRepository::class,
            AdminReviewRepositoryInterface::class => AdminReviewRepository::class,
            ReviewReportRepositoryInterface::class => ReviewReportRepository::class,
            AdminUserRepositoryInterface::class => AdminUserRepository::class,
            RoleRepositoryInterface::class => RoleRepository::class,
            AdminSalonRepositoryInterface::class => AdminSalonRepository::class,
            AdminBookingRepositoryInterface::class => AdminBookingRepository::class,
        ];

        $serviceBindings = [
            AuthServiceInterface::class => AuthService::class,
            EmailVerificationServiceInterface::class => EmailVerificationService::class,
            ProfileServiceInterface::class => ProfileService::class,
            SalonServiceInterface::class => SalonService::class,
            BookingServiceInterface::class => BookingService::class,
            ServiceCatalogServiceInterface::class => ServiceCatalogService::class,
            StaffServiceInterface::class => StaffService::class,
            PaymentServiceInterface::class => PaymentService::class,
            ReviewServiceInterface::class => ReviewService::class,
            AdminDashboardServiceInterface::class => AdminDashboardService::class,
            AdminRevenueAnalyticsServiceInterface::class => AdminRevenueAnalyticsService::class,
            AdminUserManagementServiceInterface::class => AdminUserManagementService::class,
            AdminSalonManagementServiceInterface::class => AdminSalonManagementService::class,
            AdminBookingManagementServiceInterface::class => AdminBookingManagementService::class,
            AdminPackageManagementServiceInterface::class => AdminPackageManagementService::class,
            AdminEmailTemplateManagementServiceInterface::class => AdminEmailTemplateManagementService::class,
            AdminPaymentInstructionManagementServiceInterface::class => AdminPaymentInstructionManagementService::class,
            AdminSubscriptionManagementServiceInterface::class => AdminSubscriptionManagementService::class,
            AdminAuditLogServiceInterface::class => AdminAuditLogService::class,
            AdminSettingsServiceInterface::class => AdminSettingsService::class,
            AdminServiceManagementServiceInterface::class => AdminServiceManagementService::class,
            AdminStaffManagementServiceInterface::class => AdminStaffManagementService::class,
            AdminReviewManagementServiceInterface::class => AdminReviewManagementService::class,
            OwnerDashboardServiceInterface::class => OwnerDashboardService::class,
            OwnerReportServiceInterface::class => OwnerReportService::class,
            OwnerSubscriptionServiceInterface::class => OwnerSubscriptionService::class,
            OwnerPaymentInstructionServiceInterface::class => OwnerPaymentInstructionService::class,
            OwnerPackageLimitServiceInterface::class => OwnerPackageLimitService::class,
            OwnerWorkScheduleServiceInterface::class => \App\Services\Owner\OwnerWorkScheduleService::class,
            OwnerSalonSettingsServiceInterface::class => OwnerSalonSettingsService::class,
            StaffPortalServiceInterface::class => \App\Services\Staff\StaffPortalService::class,
        ];

        foreach (array_merge($repositoryBindings, $serviceBindings) as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
