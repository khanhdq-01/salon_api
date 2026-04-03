<?php

/**
 * Customer-facing API routes.
 * Auth/profile: /api/auth/*, /api/profile/*
 * V1 routes:    /api/v1/*
 */

use App\Http\Controllers\Api\V1\Customer\AppDownloadSettingsController;
use App\Http\Controllers\Api\V1\Customer\BookingAvailableSlotsController;
use App\Http\Controllers\Api\V1\Customer\BookingCancelController;
use App\Http\Controllers\Api\V1\Customer\BookingController;
use App\Http\Controllers\Api\V1\Customer\BookingRescheduleController;
use App\Http\Controllers\Api\V1\Customer\CustomerNotificationController;
use App\Http\Controllers\Api\V1\Customer\EmailVerificationController;
use App\Http\Controllers\Api\V1\Customer\LoginController;
use App\Http\Controllers\Api\V1\Customer\LogoutController;
use App\Http\Controllers\Api\V1\Customer\PasswordController;
use App\Http\Controllers\Api\V1\Customer\PaymentCallbackController;
use App\Http\Controllers\Api\V1\Customer\PaymentController;
use App\Http\Controllers\Api\V1\Customer\PopularServiceController;
use App\Http\Controllers\Api\V1\Customer\ProfileController;
use App\Http\Controllers\Api\V1\Customer\RegisterController;
use App\Http\Controllers\Api\V1\Customer\ReviewController;
use App\Http\Controllers\Api\V1\Customer\ReviewModerationController;
use App\Http\Controllers\Api\V1\Customer\SalonHairstyleController;
use App\Http\Controllers\Api\V1\Customer\SalonSearchController;
use App\Http\Controllers\Api\V1\Customer\ServiceSearchController;
use App\Http\Controllers\Api\V1\Customer\TrendingHairstyleController;
use Illuminate\Support\Facades\Route;

// ── Auth & profile (legacy paths, no /v1 prefix) ─────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class)->middleware('throttle:auth-register');
    Route::post('login', LoginController::class);
    Route::get('email/verify', [EmailVerificationController::class, 'verify']);
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:auth-resend-verification');
    Route::post('reset-password', [PasswordController::class, 'reset']);
    Route::post('forgot-password', [PasswordController::class, 'forgot'])
        ->middleware('throttle:auth-forgot-password');

    Route::middleware(['auth:api', 'check.token.version'])->group(function () {
        Route::post('change-password', [PasswordController::class, 'change']);
        Route::post('logout', [LogoutController::class, 'logout']);
        Route::post('logout-all', [LogoutController::class, 'logoutAll']);
    });
});

Route::middleware(['auth:api', 'check.token.version'])->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'show']);
    Route::put('/', [ProfileController::class, 'update']);
    Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->middleware('throttle:upload');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
});

// ── V1 customer routes ───────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {
    Route::middleware('throttle:booking-slots')->group(function () {
        Route::get('/available-slots', [BookingAvailableSlotsController::class, 'index']);
    });

    Route::get('/settings/app-download', AppDownloadSettingsController::class);

    Route::middleware(['auth:api', 'check.token.version'])->prefix('bookings')->group(function () {
        Route::middleware('throttle:booking-create')->group(function () {
            Route::post('/', [BookingController::class, 'store']);
        });

        Route::middleware('throttle:booking-mutate')->group(function () {
            Route::patch('/{id}/cancel', BookingCancelController::class);
            Route::patch('/{id}/reschedule', BookingRescheduleController::class);
        });

        Route::get('/', [BookingController::class, 'index']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);
    });

    Route::prefix('salons')->group(function () {
        Route::get('/search', [SalonSearchController::class, 'search']);
        Route::get('/', [SalonSearchController::class, 'search']);
        Route::middleware('throttle:booking-slots')->group(function () {
            Route::get('/{salonId}/available-slots', [BookingAvailableSlotsController::class, 'bySalon']);
        });
        Route::get('/{salonId}/reviews', [ReviewController::class, 'index']);
        Route::get('/{salonId}/hairstyles', [SalonHairstyleController::class, 'index']);
        Route::get('/{salonId}/hairstyles/{styleId}', [SalonHairstyleController::class, 'show']);
        Route::get('/{id}', [\App\Http\Controllers\Api\V1\Owner\SalonController::class, 'show']);
    });

    Route::prefix('services')->group(function () {
        Route::get('/popular', PopularServiceController::class);
        Route::get('/search', ServiceSearchController::class);
        Route::get('/', [\App\Http\Controllers\Api\V1\Owner\ServiceController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\Api\V1\Owner\ServiceController::class, 'show']);
    });

    Route::middleware(['auth:api', 'check.token.version'])->group(function () {
        Route::post('/salons/{salonId}/reviews', [ReviewController::class, 'storeForSalon']);
        Route::post('/bookings/{bookingId}/review', [ReviewController::class, 'store']);
        Route::put('/reviews/{id}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
        Route::post('/reviews/{id}/report', [ReviewModerationController::class, 'report']);

        Route::post('/bookings/{bookingId}/payment', [PaymentController::class, 'store']);
        Route::get('/bookings/{bookingId}/payment', [PaymentController::class, 'show']);
    });

    Route::post('/webhooks/payment/{provider}', PaymentCallbackController::class);

    Route::prefix('customer/notifications')->group(function () {
        Route::get('/shop', [CustomerNotificationController::class, 'shop']);

        Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->group(function () {
            Route::get('/shop/unread-count', [CustomerNotificationController::class, 'unreadCount']);
            Route::patch('/shop/read', [CustomerNotificationController::class, 'markRead']);
        });
    });

    Route::prefix('trending')->group(function () {
        Route::get('/hairstyles', [TrendingHairstyleController::class, 'index']);
    });
});
