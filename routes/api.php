<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ParkingLocationController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\UserSettingsController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AdminController;

// Health check endpoint
Route::get('/', function () {
    return response()->json(['status' => 'ok', 'message' => 'API is running']);
});

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public Parking Locations (should be visible to everyone)
Route::get('/parking-locations', [ParkingLocationController::class, 'index']);
Route::get('/parking-locations/{id}', [ParkingLocationController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'user']);
    
    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    
    // User Settings
    Route::put('/user', [UserSettingsController::class, 'updateProfile']);
    Route::put('/settings', [UserSettingsController::class, 'updateSettings']);
    Route::put('/user/password', [UserSettingsController::class, 'updatePassword']);
    Route::delete('/user/account', [UserSettingsController::class, 'deleteAccount']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/parking-locations', [ParkingLocationController::class, 'store']);
        Route::put('/parking-locations/{id}', [ParkingLocationController::class, 'update']);
        Route::delete('/parking-locations/{id}', [ParkingLocationController::class, 'destroy']);
        
        // Admin dashboard endpoints
        Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
        Route::get('/admin/payments', [AdminController::class, 'getAllPayments']);
        Route::get('/admin/stats', [AdminController::class, 'getStats']);
    });
});