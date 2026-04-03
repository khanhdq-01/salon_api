<?php

/**
 * Legacy frontend paths: /api/owner/* → same handlers as /api/v1/*
 */
use App\Http\Controllers\Api\V1\Customer\BookingCancelController;
use App\Http\Controllers\Api\V1\Customer\BookingController;
use App\Http\Controllers\Api\V1\Customer\BookingRescheduleController;
use App\Http\Controllers\Api\V1\Owner\BookingStatusController;
use App\Http\Controllers\Api\V1\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Api\V1\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Api\V1\Owner\PaymentRefundController;
use App\Http\Controllers\Api\V1\Owner\SalonImageController;
use App\Http\Controllers\Api\V1\Owner\ServiceController;
use App\Http\Controllers\Api\V1\Owner\StaffAssignmentController;
use App\Http\Controllers\Api\V1\Owner\StaffController;
use App\Http\Controllers\Api\V1\Owner\StaffScheduleController;
use App\Http\Controllers\Api\V1\Owner\WorkScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.token.version', 'role:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', OwnerDashboardController::class);
    Route::get('/reports', OwnerReportController::class);
    Route::get('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'mine']);
    Route::post('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'storeMine']);
    Route::put('/salon', [\App\Http\Controllers\Api\V1\Owner\SalonOwnerController::class, 'updateMine']);
    Route::get('/settings', [\App\Http\Controllers\Api\V1\Owner\SalonSettingsController::class, 'show']);
    Route::put('/settings', [\App\Http\Controllers\Api\V1\Owner\SalonSettingsController::class, 'update']);
    Route::post('/salon/images', [SalonImageController::class, 'store'])->middleware('throttle:upload');
    Route::delete('/salon/images/{imageId}', [SalonImageController::class, 'destroy']);

    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);
        Route::patch('/{id}/confirm', [BookingStatusController::class, 'confirm']);
        Route::patch('/{id}/complete', [BookingStatusController::class, 'complete']);
        Route::patch('/{id}/cancel', BookingCancelController::class);
        Route::patch('/{id}/reschedule', BookingRescheduleController::class);
        Route::post('/{bookingId}/payment/refund', PaymentRefundController::class);
    });

    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::get('/{id}', [ServiceController::class, 'show']);
        Route::put('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
    });

    Route::prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index']);
        Route::post('/', [StaffController::class, 'store']);
        Route::get('/{id}', [StaffController::class, 'show'])->whereUuid('id');
        Route::put('/{id}', [StaffController::class, 'update'])->whereUuid('id');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->whereUuid('id');
        Route::put('/{id}/schedule', [StaffScheduleController::class, 'update'])->whereUuid('id');
        Route::put('/{id}/services', [StaffAssignmentController::class, 'assignServices'])->whereUuid('id');
    });

    Route::prefix('work-schedules')->group(function () {
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

    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'destroy']);
        Route::post('/broadcast', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'broadcast']);
        Route::get('/history', [\App\Http\Controllers\Api\V1\Owner\OwnerNotificationController::class, 'history']);
    });
});
