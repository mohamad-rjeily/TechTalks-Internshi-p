<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/registerpage',[AuthController::class,'registerPage'])->name('registerPage');
Route::get('/loginpage',[AuthController::class,'loginPage'])->name('loginPage');

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