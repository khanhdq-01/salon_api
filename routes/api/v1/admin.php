<?php

use App\Http\Controllers\Api\V1\Admin\AuditLogController;
use App\Http\Controllers\Api\V1\Admin\BookingManagementController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\EmailTemplateManagementController;
use App\Http\Controllers\Api\V1\Admin\OwnerManagementController;
use App\Http\Controllers\Api\V1\Admin\PackageManagementController;
use App\Http\Controllers\Api\V1\Admin\PaymentInstructionManagementController;
use App\Http\Controllers\Api\V1\Admin\ReviewManagementController;
use App\Http\Controllers\Api\V1\Admin\RevenueAnalyticsController;
use App\Http\Controllers\Api\V1\Admin\SalonManagementController;
use App\Http\Controllers\Api\V1\Admin\ServiceManagementController;
use App\Http\Controllers\Api\V1\Admin\SettingsController;
use App\Http\Controllers\Api\V1\Admin\StaffManagementController;
use App\Http\Controllers\Api\V1\Admin\SubscriptionManagementController;
use App\Http\Controllers\Api\V1\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
Route::middleware(['auth:api', 'check.token.version', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::get('/revenue-analytics', [RevenueAnalyticsController::class, 'index']);

    // Users (customers + general)
    Route::get('/users', [UserManagementController::class, 'index']);
    Route::post('/users', [UserManagementController::class, 'store']);
    Route::get('/users/{id}', [UserManagementController::class, 'show']);
    Route::put('/users/{id}', [UserManagementController::class, 'update']);
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);
    Route::patch('/users/{id}/lock', [UserManagementController::class, 'lock']);
    Route::patch('/users/{id}/unlock', [UserManagementController::class, 'unlock']);
    Route::patch('/users/{id}/password', [UserManagementController::class, 'changePassword']);
    Route::patch('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword']);
    Route::patch('/users/{id}/profile', [UserManagementController::class, 'updateProfile']);
    Route::patch('/users/{id}/role', [UserManagementController::class, 'changeRole']);

    // Owners
    Route::get('/owners', [OwnerManagementController::class, 'index']);
    Route::post('/owners', [OwnerManagementController::class, 'store']);
    Route::get('/owners/{id}', [OwnerManagementController::class, 'show']);
    Route::put('/owners/{id}', [OwnerManagementController::class, 'update']);
    Route::delete('/owners/{id}', [OwnerManagementController::class, 'destroy']);
    Route::patch('/owners/{id}/lock', [OwnerManagementController::class, 'lock']);
    Route::patch('/owners/{id}/unlock', [OwnerManagementController::class, 'unlock']);
    Route::patch('/owners/{id}/password', [OwnerManagementController::class, 'changePassword']);
    Route::patch('/owners/{id}/reset-password', [OwnerManagementController::class, 'resetPassword']);
    Route::patch('/owners/{id}/profile', [OwnerManagementController::class, 'updateProfile']);
    Route::patch('/owners/{id}/transfer-salon', [OwnerManagementController::class, 'transferSalon']);

    // Salons
    Route::get('/salons', [SalonManagementController::class, 'index']);
    Route::post('/salons', [SalonManagementController::class, 'store']);
    Route::get('/salons/{id}', [SalonManagementController::class, 'show']);
    Route::put('/salons/{id}', [SalonManagementController::class, 'update']);
    Route::delete('/salons/{id}', [SalonManagementController::class, 'destroy']);
    Route::patch('/salons/{id}/restore', [SalonManagementController::class, 'restore']);
    Route::patch('/salons/{id}/approve', [SalonManagementController::class, 'approve']);
    Route::patch('/salons/{id}/reject', [SalonManagementController::class, 'reject']);
    Route::patch('/salons/{id}/activate', [SalonManagementController::class, 'activate']);
    Route::patch('/salons/{id}/deactivate', [SalonManagementController::class, 'deactivate']);
    Route::patch('/salons/{id}/lock', [SalonManagementController::class, 'lock']);
    Route::patch('/salons/{id}/unlock', [SalonManagementController::class, 'unlock']);
    Route::patch('/salons/{id}/owner', [SalonManagementController::class, 'changeOwner']);
    Route::patch('/salons/{id}/profile', [SalonManagementController::class, 'updateProfile']);

    // Bookings
    Route::get('/bookings', [BookingManagementController::class, 'index']);
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show']);
    Route::patch('/bookings/{id}/confirm', [BookingManagementController::class, 'confirm']);
    Route::patch('/bookings/{id}/complete', [BookingManagementController::class, 'complete']);
    Route::patch('/bookings/{id}/cancel', [BookingManagementController::class, 'cancel']);
    Route::patch('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [BookingManagementController::class, 'destroy']);

    // Services
    Route::get('/services', [ServiceManagementController::class, 'index']);
    Route::post('/services', [ServiceManagementController::class, 'store']);
    Route::put('/services/{id}', [ServiceManagementController::class, 'update']);
    Route::delete('/services/{id}', [ServiceManagementController::class, 'destroy']);
    Route::patch('/services/{id}/activate', [ServiceManagementController::class, 'activate']);
    Route::patch('/services/{id}/deactivate', [ServiceManagementController::class, 'deactivate']);

    // Staff
    Route::get('/staff', [StaffManagementController::class, 'index']);
    Route::post('/staff', [StaffManagementController::class, 'store']);
    Route::put('/staff/{id}', [StaffManagementController::class, 'update']);
    Route::delete('/staff/{id}', [StaffManagementController::class, 'destroy']);
    Route::patch('/staff/{id}/activate', [StaffManagementController::class, 'activate']);
    Route::patch('/staff/{id}/deactivate', [StaffManagementController::class, 'deactivate']);
    Route::patch('/staff/{id}/salon', [StaffManagementController::class, 'changeSalon']);

    // Reviews
    Route::get('/reviews', [ReviewManagementController::class, 'index']);
    Route::get('/reviews/{id}', [ReviewManagementController::class, 'show']);
    Route::patch('/reviews/{id}/hide', [ReviewManagementController::class, 'hide']);
    Route::patch('/reviews/{id}/show', [ReviewManagementController::class, 'showReview']);
    Route::delete('/reviews/{id}', [ReviewManagementController::class, 'destroy']);
    Route::get('/review-reports', [ReviewManagementController::class, 'reports']);
    Route::patch('/review-reports/{id}/resolve', [ReviewManagementController::class, 'resolveReport']);

    // Packages, subscriptions, audit, settings
    Route::get('/packages', [PackageManagementController::class, 'index']);
    Route::post('/packages', [PackageManagementController::class, 'store']);
    Route::put('/packages/{id}', [PackageManagementController::class, 'update']);
    Route::delete('/packages/{id}', [PackageManagementController::class, 'destroy']);

    Route::get('/subscriptions', [SubscriptionManagementController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionManagementController::class, 'store']);
    Route::get('/subscriptions/{id}', [SubscriptionManagementController::class, 'show']);
    Route::put('/subscriptions/{id}', [SubscriptionManagementController::class, 'update']);
    Route::patch('/subscriptions/{id}/approve', [SubscriptionManagementController::class, 'approve']);
    Route::patch('/subscriptions/{id}/reject', [SubscriptionManagementController::class, 'reject']);
    Route::delete('/subscriptions/{id}', [SubscriptionManagementController::class, 'destroy']);

    Route::get('/payment-instructions', [PaymentInstructionManagementController::class, 'index']);
    Route::post('/payment-instructions', [PaymentInstructionManagementController::class, 'store']);
    Route::get('/payment-instructions/{id}', [PaymentInstructionManagementController::class, 'show']);
    Route::put('/payment-instructions/{id}', [PaymentInstructionManagementController::class, 'update']);
    Route::patch('/payment-instructions/{id}/activate', [PaymentInstructionManagementController::class, 'activate']);
    Route::delete('/payment-instructions/{id}', [PaymentInstructionManagementController::class, 'destroy']);

    Route::get('/email-templates', [EmailTemplateManagementController::class, 'index']);
    Route::get('/email-templates/{id}', [EmailTemplateManagementController::class, 'show']);
    Route::put('/email-templates/{id}', [EmailTemplateManagementController::class, 'update']);

    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::delete('/audit-logs', [AuditLogController::class, 'destroyAll']);
    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show']);

    Route::get('/settings', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);
});
});
