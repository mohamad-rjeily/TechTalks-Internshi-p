<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AdminController;

// ===================== API Routes =====================

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::patch('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('medicines')->group(function () {
    Route::get('/', [MedicineController::class, 'index']);
    Route::post('/', [MedicineController::class, 'store']);
    Route::get('/{id}', [MedicineController::class, 'show']);
    Route::put('/{id}', [MedicineController::class, 'update']);
    Route::patch('/{id}', [MedicineController::class, 'update']);
    Route::delete('/{id}', [MedicineController::class, 'destroy']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::patch('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::prefix('donations')->group(function() {
    Route::get('/', [DonationController::class, 'apiIndex']);
    Route::post('/', [DonationController::class, 'apiStore']);
    Route::get('/{id}', [DonationController::class, 'apiShow']);
    Route::put('/{id}', [DonationController::class, 'apiUpdate']);
    Route::patch('/{id}', [DonationController::class, 'apiUpdate']);
    Route::delete('/{id}', [DonationController::class, 'apiDestroy']);
});

Route::prefix('notifications')->group(function () {
    Route::get('/user/{userId}', [NotificationController::class, 'indexApi']);
    Route::post('/', [NotificationController::class, 'storeApi']);
    Route::get('/{id}', [NotificationController::class, 'showApi']);
    Route::put('/{id}', [NotificationController::class, 'updateApi']);
    Route::patch('/{id}/read', [NotificationController::class, 'markAsReadApi']);
    Route::delete('/{id}', [NotificationController::class, 'destroyApi']);
});

// ================= REQUESTS =================
Route::prefix('requests')->group(function() {
    Route::get('/', [RequestController::class, 'indexApi']);
    Route::post('/', [RequestController::class, 'storeApi']);
    Route::get('/{id}', [RequestController::class, 'showApi']);
    Route::patch('/{id}', [RequestController::class, 'updateApi']);
    Route::put('/{id}', [RequestController::class, 'updateApi']);
    Route::delete('/{id}', [RequestController::class, 'destroyApi']);
});

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'indexApi']);
    Route::post('/', [ReportController::class, 'storeApi']);
    Route::get('/{id}', [ReportController::class, 'showApi']);
    Route::patch('/{id}', [ReportController::class, 'updateApi']);
    Route::put('/{id}', [ReportController::class, 'updateApi']);
    Route::delete('/{id}', [ReportController::class, 'destroyApi']);
});

Route::prefix('audit_logs')->group(function () {
    Route::get('/', [AuditLogController::class, 'indexApi']);
    Route::post('/', [AuditLogController::class, 'storeApi']);
    Route::get('/{id}', [AuditLogController::class, 'showApi']);
    Route::patch('/{id}', [AuditLogController::class, 'updateApi']);
    Route::put('/{id}', [AuditLogController::class, 'updateApi']);
    Route::delete('/{id}', [AuditLogController::class, 'destroyApi']);
});

Route::prefix('admins')->group(function () {
    Route::get('/', [AdminController::class, 'indexApi']);
    Route::get('/{id}', [AdminController::class, 'showApi']);
    Route::post('/', [AdminController::class, 'storeApi']);
    Route::put('/{id}', [AdminController::class, 'updateApi']);
    Route::patch('/{id}', [AdminController::class, 'updateApi']);
    Route::delete('/{id}', [AdminController::class, 'destroyApi']);
});