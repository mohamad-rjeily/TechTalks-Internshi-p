<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

use App\Http\Controllers\MedicineController;

Route::prefix('medicines')->group(function () {
    Route::get('/', [MedicineController::class, 'index']);
    Route::post('/', [MedicineController::class, 'store']);
    Route::get('/{id}', [MedicineController::class, 'show']);
    Route::put('/{id}', [MedicineController::class, 'update']);
    Route::patch('/{id}', [MedicineController::class, 'update']);
    Route::delete('/{id}', [MedicineController::class, 'destroy']);
});
use App\Http\Controllers\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::patch('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});
use App\Http\Controllers\DonationController;

Route::prefix('donations')->group(function() {
    Route::get('/', [DonationController::class, 'apiIndex']);
    Route::post('/', [DonationController::class, 'apiStore']);
    Route::get('/{id}', [DonationController::class, 'apiShow']);
    Route::put('/{id}', [DonationController::class, 'apiUpdate']);   // Full update
    Route::patch('/{id}', [DonationController::class, 'apiUpdate']); // Partial update
    Route::delete('/{id}', [DonationController::class, 'apiDestroy']);
});
use App\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function () {
    Route::get('/user/{userId}', [NotificationController::class, 'indexApi']); // all notifications for user
    Route::post('/', [NotificationController::class, 'storeApi']); // create notification
    Route::get('/{id}', [NotificationController::class, 'showApi']); // show notification
    Route::put('/{id}', [NotificationController::class, 'updateApi']); // update notification
    Route::patch('/{id}/read', [NotificationController::class, 'markAsReadApi']); // mark as read
    Route::delete('/{id}', [NotificationController::class, 'destroyApi']); // delete notification
});
use App\Http\Controllers\RequestController;


// ================= REQUESTS =================
Route::get('/requests', [RequestController::class, 'indexApi']);
Route::post('/requests', [RequestController::class, 'storeApi']);
Route::get('/requests/{id}', [RequestController::class, 'showApi']);
Route::patch('/requests/{id}', [RequestController::class, 'updateApi']); // PATCH
Route::put('/requests/{id}', [RequestController::class, 'updateApi']);   // PUT enabled
Route::delete('/requests/{id}', [RequestController::class, 'destroyApi']);

use App\Http\Controllers\ReportController;
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'indexApi']);
    Route::post('/', [ReportController::class, 'storeApi']);
    Route::get('/{id}', [ReportController::class, 'showApi']);
    Route::patch('/{id}', [ReportController::class, 'updateApi']); // PATCH
    Route::put('/{id}', [ReportController::class, 'updateApi']);   // PUT enabled
    Route::delete('/{id}', [ReportController::class, 'destroyApi']);
});
use App\Http\Controllers\AuditLogController;

Route::get('/audit_logs', [AuditLogController::class, 'indexApi']);
Route::post('/audit_logs', [AuditLogController::class, 'storeApi']);
Route::get('/audit_logs/{id}', [AuditLogController::class, 'showApi']);
Route::patch('/audit_logs/{id}', [AuditLogController::class, 'updateApi']);
Route::put('/audit_logs/{id}', [AuditLogController::class, 'updateApi']);
Route::delete('/audit_logs/{id}', [AuditLogController::class, 'destroyApi']);
use App\Http\Controllers\AdminController;

Route::get('admins', [AdminController::class, 'indexApi']);
Route::get('admins/{id}', [AdminController::class, 'showApi']);
Route::post('admins', [AdminController::class, 'storeApi']);
Route::put('admins/{id}', [AdminController::class, 'updateApi']);
Route::patch('admins/{id}', [AdminController::class, 'updateApi']);
Route::delete('admins/{id}', [AdminController::class, 'destroyApi']);
