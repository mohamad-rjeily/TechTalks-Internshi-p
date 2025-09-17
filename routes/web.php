<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Web routes for Blade views
Route::get('/users', [UserController::class, 'indexWeb'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'storeWeb'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'showWeb'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'updateWeb'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroyWeb'])->name('users.destroy');

use App\Http\Controllers\MedicineController;

Route::get('/medicines', [MedicineController::class, 'indexWeb'])->name('medicines.index');
Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
Route::post('/medicines', [MedicineController::class, 'storeWeb'])->name('medicines.store');
Route::get('/medicines/{id}', [MedicineController::class, 'showWeb'])->name('medicines.show');
Route::get('/medicines/{id}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
Route::put('/medicines/{id}', [MedicineController::class, 'updateWeb'])->name('medicines.update');
Route::delete('/medicines/{id}', [MedicineController::class, 'destroyWeb'])->name('medicines.destroy');

use App\Http\Controllers\CategoryController;

Route::get('/categories', [CategoryController::class, 'indexWeb'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'storeWeb'])->name('categories.store');
Route::get('/categories/{id}', [CategoryController::class, 'showWeb'])->name('categories.show');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'updateWeb'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroyWeb'])->name('categories.destroy');
use App\Http\Controllers\DonationController;

Route::prefix('donations')->group(function() {
    Route::get('/', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/store', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::delete('/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
});
use App\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'indexWeb'])->name('notifications.index');
    Route::get('/create', [NotificationController::class, 'createWeb'])->name('notifications.create');
    Route::post('/', [NotificationController::class, 'storeWeb'])->name('notifications.store');
    Route::get('/{id}', [NotificationController::class, 'showWeb'])->name('notifications.show');
    Route::get('/{id}/edit', [NotificationController::class, 'editWeb'])->name('notifications.edit');
    Route::put('/{id}', [NotificationController::class, 'updateWeb'])->name('notifications.update');
    Route::delete('/{id}', [NotificationController::class, 'destroyWeb'])->name('notifications.destroy');
});
use App\Http\Controllers\RequestController;

Route::prefix('requests')->group(function(){
    Route::get('/', [RequestController::class,'indexWeb'])->name('requests.index');
    Route::get('/create', [RequestController::class,'createWeb'])->name('requests.create');
    Route::post('/', [RequestController::class,'storeWeb'])->name('requests.store');
    Route::get('/{id}', [RequestController::class,'showWeb'])->name('requests.show');
    Route::get('/{id}/edit', [RequestController::class,'editWeb'])->name('requests.edit');
    Route::put('/{id}', [RequestController::class,'updateWeb'])->name('requests.update');
    Route::delete('/{id}', [RequestController::class,'destroyWeb'])->name('requests.destroy');
});
use App\Http\Controllers\ReportController;

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'indexWeb'])->name('reports.index');
    Route::get('/create', [ReportController::class, 'createWeb'])->name('reports.create');
    Route::post('/', [ReportController::class, 'storeWeb'])->name('reports.store');
    Route::get('/{id}', [ReportController::class, 'showWeb'])->name('reports.show');
    Route::get('/{id}/edit', [ReportController::class, 'editWeb'])->name('reports.edit');
    Route::put('/{id}', [ReportController::class, 'updateWeb'])->name('reports.update');
    Route::delete('/{id}', [ReportController::class, 'destroyWeb'])->name('reports.destroy');
});
use App\Http\Controllers\AuditLogController;

Route::get('/audit_logs', [AuditLogController::class, 'indexWeb'])->name('audit_logs.index');
Route::get('/audit_logs/create', [AuditLogController::class, 'createWeb'])->name('audit_logs.create');
Route::post('/audit_logs', [AuditLogController::class, 'storeWeb'])->name('audit_logs.store');
Route::get('/audit_logs/{id}', [AuditLogController::class, 'showWeb'])->name('audit_logs.show');
Route::get('/audit_logs/{id}/edit', [AuditLogController::class, 'editWeb'])->name('audit_logs.edit');
Route::put('/audit_logs/{id}', [AuditLogController::class, 'updateWeb'])->name('audit_logs.update');
Route::delete('/audit_logs/{id}', [AuditLogController::class, 'destroyWeb'])->name('audit_logs.destroy');
use App\Http\Controllers\AdminController;

Route::prefix('admins')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/store', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/update/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
});
