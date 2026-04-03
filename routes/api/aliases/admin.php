<?php

/**
 * Legacy frontend paths: /api/admin/* → same handlers as /api/v1/admin/*
 */
use App\Http\Controllers\Api\V1\Admin\BookingManagementController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\SalonManagementController;
use App\Http\Controllers\Api\V1\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.token.version', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::get('/users', [UserManagementController::class, 'index']);
    Route::patch('/users/{id}/lock', [UserManagementController::class, 'lock']);
    Route::patch('/users/{id}/unlock', [UserManagementController::class, 'unlock']);
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);
    Route::get('/salons', [SalonManagementController::class, 'index']);
    Route::patch('/salons/{id}/approve', [SalonManagementController::class, 'approve']);
    Route::patch('/salons/{id}/lock', [SalonManagementController::class, 'lock']);
    Route::patch('/salons/{id}/unlock', [SalonManagementController::class, 'unlock']);
    Route::delete('/salons/{id}', [SalonManagementController::class, 'destroy']);
    Route::get('/bookings', [BookingManagementController::class, 'index']);
});
