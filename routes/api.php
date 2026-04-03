<?php

use App\Http\Controllers\HealthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Legacy role-based paths used by Vue frontend (salonApi.js, bookingApi.js)
require __DIR__ . '/api/aliases/customer.php';
require __DIR__ . '/api/aliases/owner.php';
require __DIR__ . '/api/aliases/admin.php';
require __DIR__ . '/api/aliases/staff.php';

// Role-based V1 API (URLs unchanged)
require __DIR__ . '/api/v1/customer.php';
require __DIR__ . '/api/v1/staff.php';
require __DIR__ . '/api/v1/owner.php';
require __DIR__ . '/api/v1/admin.php';
