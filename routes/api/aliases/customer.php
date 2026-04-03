<?php

/**
 * Legacy frontend paths: /api/customer/* → same handlers as /api/v1/*
 */
use App\Http\Controllers\Api\V1\Customer\BookingCancelController;
use App\Http\Controllers\Api\V1\Customer\BookingController;
use App\Http\Controllers\Api\V1\Customer\BookingRescheduleController;
use App\Http\Controllers\Api\V1\Customer\FavoriteHairstyleController;
use App\Http\Controllers\Api\V1\Customer\FavoriteSalonController;
use App\Http\Controllers\Api\V1\Customer\PaymentController;
use App\Http\Controllers\Api\V1\Customer\ReviewController;
use App\Http\Controllers\Api\V1\Customer\ReviewModerationController;
use App\Http\Controllers\Api\V1\Owner\SalonController;
use App\Http\Controllers\Api\V1\Customer\SalonSearchController;
use App\Http\Controllers\Api\V1\Customer\SearchHistoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::prefix('salons')->group(function () {
        Route::get('/', [SalonSearchController::class, 'search']);
        Route::get('/{id}', [SalonController::class, 'show']);
        Route::get('/{salonId}/reviews', [ReviewController::class, 'index']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('salons')->group(function () {
        Route::post('/{salonId}/reviews', [ReviewController::class, 'storeForSalon']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('bookings')->group(function () {
        Route::middleware('throttle:booking-create')->group(function () {
            Route::post('/', [BookingController::class, 'store']);
        });

        Route::middleware('throttle:booking-mutate')->group(function () {
            Route::patch('/{id}/cancel', BookingCancelController::class);
            Route::patch('/{id}/reschedule', BookingRescheduleController::class);
        });

        Route::get('/', [BookingController::class, 'index']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::post('/{bookingId}/review', [ReviewController::class, 'store']);
        Route::post('/{bookingId}/payment', [PaymentController::class, 'store']);
        Route::get('/{bookingId}/payment', [PaymentController::class, 'show']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('reviews')->group(function () {
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
        Route::post('/{id}/report', [ReviewModerationController::class, 'report']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('favorites/salons')->group(function () {
        Route::get('/', [FavoriteSalonController::class, 'index']);
        Route::post('/', [FavoriteSalonController::class, 'store']);
        Route::delete('/{salonId}', [FavoriteSalonController::class, 'destroy']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('favorites/hairstyles')->group(function () {
        Route::get('/', [FavoriteHairstyleController::class, 'index']);
        Route::post('/', [FavoriteHairstyleController::class, 'store']);
        Route::delete('/{styleId}', [FavoriteHairstyleController::class, 'destroy']);
    });

    Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->prefix('search-history')->group(function () {
        Route::get('/', [SearchHistoryController::class, 'index']);
        Route::post('/', [SearchHistoryController::class, 'store']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/shop', [\App\Http\Controllers\Api\V1\Customer\CustomerNotificationController::class, 'shop']);

        Route::middleware(['auth:api', 'check.token.version', 'role:customer'])->group(function () {
            Route::get('/shop/unread-count', [\App\Http\Controllers\Api\V1\Customer\CustomerNotificationController::class, 'unreadCount']);
            Route::patch('/shop/read', [\App\Http\Controllers\Api\V1\Customer\CustomerNotificationController::class, 'markRead']);
        });
    });
});
