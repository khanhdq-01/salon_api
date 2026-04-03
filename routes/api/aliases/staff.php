<?php

/**
 * Legacy frontend paths: /api/staff/* → same handlers as /api/v1/staff/*
 */

use App\Http\Controllers\Api\V1\Staff\StaffPortalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.token.version', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/profile', [StaffPortalController::class, 'profile']);
    Route::put('/profile', [StaffPortalController::class, 'updateProfile']);
    Route::get('/dashboard', [StaffPortalController::class, 'dashboard']);
    Route::get('/calendar', [StaffPortalController::class, 'calendar']);
    Route::patch('/bookings/{id}/complete', [StaffPortalController::class, 'completeBooking']);
    Route::get('/schedules', [StaffPortalController::class, 'schedules']);
    Route::get('/work-schedules', [StaffPortalController::class, 'workSchedules']);
    Route::put('/schedules', [StaffPortalController::class, 'submitSchedules']);
    Route::get('/reports', [StaffPortalController::class, 'report']);
});
