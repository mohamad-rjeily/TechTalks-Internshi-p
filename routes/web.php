<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\UserController;


// Web routes for Blade views
Route::get('/users', [UserController::class, 'indexWeb'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'storeWeb'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'showWeb'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'updateWeb'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroyWeb'])->name('users.destroy');



Route::get('/medicines', [MedicineController::class, 'indexWeb'])->name('medicines.index');
Route::get('/browse-medicines', [MedicineController::class, 'browse'])->name('medicines.browse');
Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
Route::post('/medicines', [MedicineController::class, 'storeWeb'])->name('medicines.store');
Route::get('/medicines/{id}', [MedicineController::class, 'showWeb'])->name('medicines.show');
Route::get('/medicines/{id}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
Route::put('/medicines/{id}', [MedicineController::class, 'updateWeb'])->name('medicines.update');
Route::delete('/medicines/{id}', [MedicineController::class, 'destroyWeb'])->name('medicines.destroy');

use App\Http\Controllers\CategoryController;
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
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileSettingsController;

// =========================================================================
// PUBLIC & USER AUTHENTICATION ROUTES (Using AuthController)
// =========================================================================

// Landing/Public Pages
Route::get('/dashboard', [DashboardController::class, 'indexWeb'])->name('dashboard');

// Reports routes
Route::prefix('reports')->group(function() {
    Route::get('/', [ReportController::class, 'indexWeb'])->name('reports.index');
    Route::get('/create', [ReportController::class, 'createWeb'])->name('reports.create');
    Route::post('/', [ReportController::class, 'storeWeb'])->name('reports.store');
    Route::get('/{id}', [ReportController::class, 'showWeb'])->name('reports.show');
    Route::get('/{id}/edit', [ReportController::class, 'editWeb'])->name('reports.edit');
    Route::put('/{id}', [ReportController::class, 'updateWeb'])->name('reports.update');
    Route::delete('/{id}', [ReportController::class, 'destroyWeb'])->name('reports.destroy');
});

// User Registration and Login Pages
Route::get('/registerpage',[AuthController::class,'registerPage'])->name('registerPage');
Route::get('/loginpage',[AuthController::class,'loginPage'])->name('loginPage');
Route::get('/login',[AuthController::class,'loginPage'])->name('login');

// Add GET route for /login to redirect to loginpage
Route::get('/login', function() {
    return redirect()->route('loginPage');
});

// User Registration and Login Handlers
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::middleware('checkUser')->group(function(){
    Route::get('/admin',[AuthController::class,'adminDashboard'])->name('admin');
});
Route::middleware('auth')->group(function(){
    Route::get('/email/verify',[AuthController::class,'verifyNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[AuthController::class,'verifyEmail'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification',[AuthController::class,'verifyHandler'] )->middleware('throttle:6,1')->name('verification.send');
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});

Route::middleware('guest')->group(function(){
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request'); 
    Route::post('/forgot-password',[ResetPasswordController::class,'passwordEmail'] )->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class,'passwordReset'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class,'passwordUpdate'])->name('password.update');
});

// Welcome route
Route::get('/', function () {
    return view('welcome');
});

// A
// Admin Login Form (GET request to show the form)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');

// Admin Login Handler (POST request to submit the form)
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Admin Logout (Supports both GET and POST)
Route::match(['get', 'post'], '/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');


// =========================================================================
// ADMIN PROTECTED ROUTES (Requires 'admin.auth' middleware)
// =========================================================================
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management (Admin Views: admin.users.*)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'indexWeb'])->name('index');
        Route::get('/create', [UserController::class, 'createWeb'])->name('create');
        Route::post('/', [UserController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [UserController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroyWeb'])->name('destroy');
    });

    // Medicines Management (Admin Views: admin.medicines.*)
    Route::prefix('medicines')->name('medicines.')->group(function () {
        Route::get('/', [MedicineController::class, 'indexWeb'])->name('index');
        Route::get('/create', [MedicineController::class, 'createWeb'])->name('create');
        Route::post('/', [MedicineController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [MedicineController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [MedicineController::class, 'editWeb'])->name('edit');
        Route::put('/{id}', [MedicineController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [MedicineController::class, 'destroyWeb'])->name('destroy');
    });

    // Categories Management (Admin Views: admin.categories.*)
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'indexWeb'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [CategoryController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroyWeb'])->name('destroy');
    });

    // Donations Management (Admin Views: admin.donations.*)
    Route::prefix('donations')->name('donations.')->group(function() {
        Route::get('/', [DonationController::class, 'indexWeb'])->name('index');
        Route::get('/create', [DonationController::class, 'createWeb'])->name('create');
        Route::post('/', [DonationController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [DonationController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [DonationController::class, 'editWeb'])->name('edit');
        Route::put('/{id}', [DonationController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [DonationController::class, 'destroyWeb'])->name('destroy');
    });

    // Requests Management (Admin Views: admin.requests.*)
    Route::prefix('requests')->name('requests.')->group(function(){
        Route::get('/', [RequestController::class,'indexWeb'])->name('index');
        Route::get('/create', [RequestController::class,'createWeb'])->name('create');
        Route::post('/', [RequestController::class,'storeWeb'])->name('store');
        Route::get('/{id}', [RequestController::class,'showWeb'])->name('show');
        Route::get('/{id}/edit', [RequestController::class,'editWeb'])->name('edit');
        Route::put('/{id}', [RequestController::class,'updateWeb'])->name('update');
        Route::delete('/{id}', [RequestController::class,'destroyWeb'])->name('destroy');
    });

   

    // Reports Management (Admin Views: admin.reports.*)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'indexWeb'])->name('index');
        Route::get('/create', [ReportController::class, 'createWeb'])->name('create');
        Route::post('/', [ReportController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [ReportController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [ReportController::class, 'editWeb'])->name('edit');
        Route::put('/{id}', [ReportController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [ReportController::class, 'destroyWeb'])->name('destroy');
    });
    
    // Audit Logs (Admin Views: admin.audit_logs.*)
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'indexWeb'])->name('index');
        Route::get('/create', [AuditLogController::class, 'createWeb'])->name('create');
        Route::post('/', [AuditLogController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [AuditLogController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [AuditLogController::class, 'editWeb'])->name('edit');
        Route::put('/{id}', [AuditLogController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [AuditLogController::class, 'destroyWeb'])->name('destroy');
    });

    // Notifications (Admin Views: admin.notifications.*)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'indexWeb'])->name('index');
        Route::get('/create', [NotificationController::class, 'createWeb'])->name('create');
        Route::post('/', [NotificationController::class, 'storeWeb'])->name('store');
        Route::get('/{id}', [NotificationController::class, 'showWeb'])->name('show');
        Route::get('/{id}/edit', [NotificationController::class, 'editWeb'])->name('edit');
        Route::put('/{id}', [NotificationController::class, 'updateWeb'])->name('update');
        Route::delete('/{id}', [NotificationController::class, 'destroyWeb'])->name('destroy');
    });
});

// =========================================================================
// USER PROTECTED ROUTES (Requires 'auth' middleware)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    // User Profile Settings
    Route::get('/profile-settings', [ProfileSettingsController::class, 'profileSettings'])->name('profile_settings');
    Route::post('/profile-settings', [ProfileSettingsController::class, 'updateProfileInformation'])->name('update_profile_info');
    Route::post('/delete-account', [ProfileSettingsController::class, 'deleteUserAccount'])->name('delete_account');
    Route::post('/change-password', [ProfileSettingsController::class, 'changePassword'])->name('change_password');
    Route::post('/update-privacy', [ProfileSettingsController::class, 'updatePrivacySettings'])->name('update_privacy_settings');

    // Email Verification Routes
    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'verifyHandler'])->middleware(['throttle:6,1'])->name('verification.send');
    
    // Audit logs routes
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Main requests page
Route::get('/requestsPage', function () {
    return view('requests');
})->name('requests')->middleware('auth');

// Main Reports Page
Route::get('/reportsPage', function () {
    return view('reports');
})->name('reports')->middleware('auth');

// =========================================================================
// PASSWORD RESET ROUTES (No middleware)
// =========================================================================

// Forgot Password Form (Send Email)
Route::post('/forgot-password', [ResetPasswordController::class, 'passwordEmail'])->name('password.email');

// Reset Password Form (with token)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'passwordReset'])->name('password.reset');

// Handle Password Reset Update
Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->name('password.update');