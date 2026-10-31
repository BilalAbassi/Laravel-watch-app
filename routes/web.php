<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.show');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order-confirmation/{orderNumber?}', [ShopController::class, 'confirmation'])->name('order.confirmation');

Route::post('/cart/sync', [ShopController::class, 'syncCart'])->name('cart.sync');
Route::get('/cart/data', [ShopController::class, 'getCart'])->name('cart.data');
Route::post('/cart/clear', [ShopController::class, 'clearCart'])->name('cart.clear');

// Authentication Routes (UI Preview Mode — Ready for Laravel Breeze)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
