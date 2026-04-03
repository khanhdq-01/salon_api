<?php

/**
 * Owner-facing API routes: /api/v1/*
 */

use App\Http\Controllers\Api\V1\Owner\BookingStatusController;
use App\Http\Controllers\Api\V1\Owner\DashboardController;
use App\Http\Controllers\Api\V1\Owner\OwnerNotificationController;
use App\Http\Controllers\Api\V1\Owner\PaymentInstructionController;
use App\Http\Controllers\Api\V1\Owner\PaymentRefundController;
use App\Http\Controllers\Api\V1\Owner\ReportController;
use App\Http\Controllers\Api\V1\Owner\SalonController;
use App\Http\Controllers\Api\V1\Owner\SalonImageController;
use App\Http\Controllers\Api\V1\Owner\SalonStatusController;
use App\Http\Controllers\Api\V1\Owner\ServiceController;
use App\Http\Controllers\Api\V1\Owner\StaffAssignmentController;
use App\Http\Controllers\Api\V1\Owner\StaffController;
use App\Http\Controllers\Api\V1\Owner\StaffScheduleController;
use App\Http\Controllers\Api\V1\Owner\StyleOptionController;
use App\Http\Controllers\Api\V1\Owner\StyleOptionImageController;
use App\Http\Controllers\Api\V1\Owner\WorkScheduleController;
use App\Http\Controllers\Api\V1\Owner\SubscriptionController;
use App\Http\Controllers\Api\V1\Owner\SubscriptionPaymentProofController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Owner portal
    Route::middleware(['auth:api', 'check.token.version', 'role:owner'])
        ->prefix('owner')
        ->group(function () {
            Route::get('/dashboard', DashboardController::class);
            Route::get('/reports', ReportController::class);

            Route::get('/subscription', [SubscriptionController::class, 'show']);
            Route::get('/subscription/plans', [SubscriptionController::class, 'plans']);
            Route::get('/packages', [SubscriptionController::class, 'availablePackages']);
            Route::post('/subscription/payment', [SubscriptionController::class, 'submitPayment']);
            Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade']);

            Route::get('/payment-instructions', [PaymentInstructionController::class, 'show']);

            Route::get('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'mine']);
            Route::post('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'storeMine']);
            Route::put('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'updateMine']);
            Route::get('/settings', [\App\Http\Controllers\Api\V1\Owner\SalonSettingsController::class, 'show']);
            Route::put('/settings', [\App\Http\Controllers\Api\V1\Owner\SalonSettingsController::class, 'update']);
            Route::post('/salon/images', [SalonImageController::class, 'store'])->middleware('throttle:upload');
            Route::delete('/salon/images/{imageId}', [SalonImageController::class, 'destroy']);
        });

    Route::middleware(['auth:api', 'check.token.version', 'role:owner'])
        ->prefix('owner/notifications')
        ->group(function () {
            Route::get('/', [OwnerNotificationController::class, 'index']);
            Route::post('/', [OwnerNotificationController::class, 'store']);
            Route::get('/{id}', [OwnerNotificationController::class, 'show']);
            Route::put('/{id}', [OwnerNotificationController::class, 'update']);
            Route::delete('/{id}', [OwnerNotificationController::class, 'destroy']);
            Route::post('/broadcast', [OwnerNotificationController::class, 'broadcast']);
            Route::get('/history', [OwnerNotificationController::class, 'history']);
        });

    Route::middleware(['auth:api', 'check.token.version', 'role:owner'])
        ->prefix('owner/work-schedules')
        ->group(function () {
            Route::get('/calendar', [WorkScheduleController::class, 'calendar']);
            Route::get('/pending', [WorkScheduleController::class, 'pending']);
            Route::patch('/pending/approve-all', [WorkScheduleController::class, 'approveAll']);
            Route::get('/staff/{staffId}', [WorkScheduleController::class, 'staffSchedules'])->whereUuid('staffId');
            Route::post('/', [WorkScheduleController::class, 'store']);
            Route::put('/{id}', [WorkScheduleController::class, 'update'])->whereNumber('id');
            Route::delete('/{id}', [WorkScheduleController::class, 'destroy'])->whereNumber('id');
            Route::patch('/{id}/approve', [WorkScheduleController::class, 'approve'])->whereNumber('id');
            Route::patch('/{id}/reject', [WorkScheduleController::class, 'reject'])->whereNumber('id');
        });

    // Salon management
    Route::prefix('salons')->group(function () {
        Route::get('/owner/me', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'mine'])
            ->middleware(['auth:api', 'check.token.version', 'role:owner']);

        Route::middleware(['auth:api', 'check.token.version'])->group(function () {
            Route::post('/', [SalonController::class, 'store']);
            Route::put('/{id}', [SalonController::class, 'update']);
            Route::delete('/{id}', [SalonController::class, 'destroy']);
            Route::patch('/{id}/status', [SalonStatusController::class, 'update']);
        });
    });

    Route::middleware(['auth:api', 'check.token.version'])->prefix('owners')->group(function () {
        Route::get('/{ownerId}/salons', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'index']);
    });

    // Booking status (owner actions)
    Route::middleware(['auth:api', 'check.token.version'])->prefix('bookings')->group(function () {
        Route::patch('/{id}/confirm', [BookingStatusController::class, 'confirm']);
        Route::patch('/{id}/complete', [BookingStatusController::class, 'complete']);
        Route::patch('/{id}/status', [BookingStatusController::class, 'update']);
    });

    // Services CRUD
    Route::prefix('services')->group(function () {
        Route::middleware(['auth:api', 'check.token.version'])->group(function () {
            Route::post('/', [ServiceController::class, 'store']);
            Route::put('/{id}', [ServiceController::class, 'update']);
            Route::delete('/{id}', [ServiceController::class, 'destroy']);
        });
    });

    // Staff (owner CRUD — {id} must be UUID to avoid clashing with staff portal paths)
    Route::prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index']);
        Route::get('/{id}', [StaffController::class, 'show'])->whereUuid('id');

        Route::middleware(['auth:api', 'check.token.version'])->group(function () {
            Route::post('/', [StaffController::class, 'store']);
            Route::put('/{id}', [StaffController::class, 'update'])->whereUuid('id');
            Route::delete('/{id}', [StaffController::class, 'destroy'])->whereUuid('id');
            Route::put('/{id}/schedule', [StaffScheduleController::class, 'update'])->whereUuid('id');
            Route::put('/{id}/services', [StaffAssignmentController::class, 'assignServices'])->whereUuid('id');
        });
    });

    // Style options
    Route::prefix('style-options')->group(function () {
        Route::middleware(['auth:api', 'check.token.version'])->group(function () {
            Route::get('/', [StyleOptionController::class, 'index']);
            Route::post('/', [StyleOptionController::class, 'store']);
            Route::get('/{id}', [StyleOptionController::class, 'show']);
            Route::put('/{id}', [StyleOptionController::class, 'update']);
            Route::delete('/{id}', [StyleOptionController::class, 'destroy']);
        });
    });

    // Uploads
    Route::middleware(['auth:api', 'check.token.version', 'throttle:upload'])
        ->prefix('uploads')
        ->group(function () {
            Route::post('/style-option-image', [StyleOptionImageController::class, 'store']);
            Route::post('/salon-image', [SalonImageController::class, 'store']);
            Route::post('/subscription-payment-proof', [SubscriptionPaymentProofController::class, 'store']);
        });

    // Payment refund
    Route::middleware(['auth:api', 'check.token.version'])->group(function () {
        Route::post('/bookings/{bookingId}/payment/refund', PaymentRefundController::class);
    });
});
