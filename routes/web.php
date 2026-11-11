<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('home.index');
    })->name('dashboard');

    Route::get('/buscar', [ProductController::class, 'search'])->name('products.search');
    Route::post('/buscar', [ProductController::class, 'search'])->name('products.search.submit');
    Route::get('/productos', [ProductController::class, 'catalog'])->name('products.catalog');
    Route::post('/productos', [ProductController::class, 'search'])->name('products.search.legacy');
    Route::get('/lista-productos', [ProductController::class, 'storefrontIndex'])->name('products.index');
    Route::post('/lista-productos', [ProductController::class, 'search'])->name('products.search.legacy-index');
    Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/productos/{product}', [ProductController::class, 'search'])
        ->whereNumber('product')
        ->name('products.search.legacy-detail');

    Route::get('/carrito', [SaleController::class, 'cart'])->name('cart.index');
    Route::post('/carrito', [ProductController::class, 'search'])->name('products.search.legacy-cart');
    Route::post('/carrito/{product}', [SaleController::class, 'addToCart'])->name('cart.add');
    Route::delete('/carrito/{product}', [SaleController::class, 'removeFromCart'])->name('cart.remove');

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

require __DIR__.'/auth.php';
