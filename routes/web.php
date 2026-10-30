<?php

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
