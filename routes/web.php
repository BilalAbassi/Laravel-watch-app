<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomizerController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SpecAttributeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// TIMEYRAH Storefront Routes
Route::get('/', HomeController::class)->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.show');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order-confirmation/{orderNumber?}', [ShopController::class, 'confirmation'])->name('order.confirmation');

// Cart State Endpoints
Route::post('/cart/sync', [ShopController::class, 'syncCart'])->name('cart.sync');
Route::get('/cart/data', [ShopController::class, 'getCart'])->name('cart.data');
Route::post('/cart/clear', [ShopController::class, 'clearCart'])->name('cart.clear');

// Authenticated Customer Dashboard & Profile (Laravel Breeze)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/products')->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('specifications', SpecAttributeController::class);
    Route::get('customizer', [CustomizerController::class, 'index'])->name('customizer.index');
    Route::post('customizer', [CustomizerController::class, 'update'])->name('customizer.update');
});

// Laravel Breeze Authentication Routes
require __DIR__.'/auth.php';
