<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/productos', [ProductController::class, 'catalog'])->name('products.catalog');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/carrito', [SaleController::class, 'cart'])->name('cart.index');
Route::post('/carrito/{product}', [SaleController::class, 'addToCart'])->name('cart.add');
Route::delete('/carrito/{product}', [SaleController::class, 'removeFromCart'])->name('cart.remove');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthController::class, 'createRegistration'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/checkout', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/mis-compras', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/ventas/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/ventas/{sale}/pago', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/ventas/{sale}/pago', [PaymentController::class, 'store'])->name('payments.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::view('/', 'admin.index')->name('dashboard');
    Route::resource('products', ProductController::class)->except('show')->names('products');
    Route::resource('categories', CategoryController::class)->except('show')->names('categories');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
});
