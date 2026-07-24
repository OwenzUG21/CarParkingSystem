<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ParkingLocationController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\UserSettingsController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\LotManagerController;
use App\Http\Controllers\API\KeeperController;
use App\Http\Controllers\API\ChatMessageController;

// Health check endpoint
Route::get('/', function () {
    return response()->json(['status' => 'ok', 'message' => 'API is running']);
});

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/chat/messages', [ChatMessageController::class, 'store']);

// Public Parking Locations (should be visible to everyone)
Route::get('/parking-locations', [ParkingLocationController::class, 'index']);
Route::get('/parking-locations/{id}', [ParkingLocationController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'user']);

    // User chat messages (for showing admin replies)
    Route::get('/chat/messages', [ChatMessageController::class, 'userIndex']);
    
    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    
    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments/reservations/{reservation}', [PaymentController::class, 'store']);
    
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
        Route::get('/admin/chat/messages', [ChatMessageController::class, 'index']);
        // Main Admin creates a new lot + assigns a Lot Manager
        Route::post('/admin/lots', [AdminController::class, 'createLotWithManager']);
        // Manage lots
        Route::get('/admin/lots', [AdminController::class, 'getLots']);
        Route::put('/admin/lots/{id}', [AdminController::class, 'updateLot']);
        Route::delete('/admin/lots/{id}', [AdminController::class, 'deleteLot']);
        // Manage lot managers
        Route::get('/admin/managers', [AdminController::class, 'getManagers']);
        Route::post('/admin/managers', [AdminController::class, 'createManager']);
        Route::put('/admin/managers/{id}', [AdminController::class, 'updateManager']);
        Route::delete('/admin/managers/{id}', [AdminController::class, 'deleteManager']);
    });

    // Lot Manager (Spot Admin) routes - multi-tenant, scoped to manager's lots
    Route::prefix('manager')->group(function () {
        Route::get('/dashboard', [LotManagerController::class, 'dashboard']);
        Route::get('/lots', [LotManagerController::class, 'lots']);
        Route::get('/bookings', [LotManagerController::class, 'bookings']);
        Route::get('/lots/{id}/slots', [LotManagerController::class, 'getSlots']);
        Route::post('/lots/{id}/slots/configure', [LotManagerController::class, 'configureSlots']);
        Route::put('/lots/{id}', [LotManagerController::class, 'updateLot']);
        Route::get('/lots/{id}/bookings/active', [LotManagerController::class, 'activeBookings']);
        Route::get('/keepers', [LotManagerController::class, 'keepers']);
        Route::post('/keepers', [LotManagerController::class, 'storeKeeper']);
        Route::get('/keepers/activity', [LotManagerController::class, 'keeperActivity']);
        Route::put('/keepers/{assignmentId}', [LotManagerController::class, 'updateKeeper']);
        Route::delete('/keepers/{assignmentId}', [LotManagerController::class, 'destroyKeeper']);
        Route::post('/validations', [LotManagerController::class, 'createValidation']);
        Route::get('/validations', [LotManagerController::class, 'validations']);
    });

    // Keeper routes
    Route::prefix('keeper')->group(function () {
        Route::get('/lot', [KeeperController::class, 'lot']);
        Route::get('/bookings/active', [KeeperController::class, 'activeBookings']);
        Route::post('/check-in', [KeeperController::class, 'checkIn']);
        Route::post('/check-out', [KeeperController::class, 'checkOut']);
        Route::get('/payment-status', [KeeperController::class, 'paymentStatus']);
    });
});
