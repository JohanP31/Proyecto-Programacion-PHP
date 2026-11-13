<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController; // <--- 1. Importar el nuevo controlador de usuarios
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'authorized:dashboard.view'])
    ->name('home.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('home.index');
    })->middleware('authorized:dashboard.view')->name('dashboard');

    Route::get('/buscar', [ProductController::class, 'search'])
        ->middleware('authorized:products.search')->name('products.search');
    Route::post('/buscar', [ProductController::class, 'search'])
        ->middleware('authorized:products.search')->name('products.search.submit');
    Route::get('/productos', [ProductController::class, 'catalog'])
        ->middleware('authorized:products.view')->name('products.catalog');
    Route::post('/productos', [ProductController::class, 'search'])
        ->middleware('authorized:products.search')->name('products.search.legacy');
    Route::get('/lista-productos', [ProductController::class, 'storefrontIndex'])
        ->middleware('authorized:products.view')->name('products.index');
    Route::post('/lista-productos', [ProductController::class, 'search'])
        ->middleware('authorized:products.search')->name('products.search.legacy-index');
    Route::get('/productos/{product}', [ProductController::class, 'show'])
        ->middleware('authorized:products.view')->name('products.show');
    Route::post('/productos/{product}', [ProductController::class, 'search'])
        ->whereNumber('product')
        ->middleware('authorized:products.search')
        ->name('products.search.legacy-detail');

    Route::get('/carrito', [SaleController::class, 'cart'])
        ->middleware('authorized:cart.view')->name('cart.index');
    Route::post('/carrito', [ProductController::class, 'search'])
        ->middleware('authorized:products.search')->name('products.search.legacy-cart');
    Route::post('/carrito/{product}', [SaleController::class, 'addToCart'])
        ->middleware('authorized:cart.add')->name('cart.add');
    Route::delete('/carrito/{product}', [SaleController::class, 'removeFromCart'])
        ->middleware('authorized:cart.remove')->name('cart.remove');

    Route::post('/checkout', [SaleController::class, 'store'])
        ->middleware('authorized:sales.create')->name('sales.store');
    Route::get('/mis-compras', [SaleController::class, 'index'])
        ->middleware('authorized:sales.view|sales.view_all')->name('sales.index');
    Route::get('/ventas/{sale}', [SaleController::class, 'show'])
        ->middleware('authorized:sales.view|sales.view_all')->name('sales.show');
    Route::get('/ventas/{sale}/pago', [PaymentController::class, 'create'])
        ->middleware('authorized:payments.create')->name('payments.create');
    Route::post('/ventas/{sale}/pago', [PaymentController::class, 'store'])
        ->middleware('authorized:payments.store')->name('payments.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::view('/', 'admin.index')
        ->middleware('authorized:admin.dashboard.view')->name('dashboard');

    Route::resource('products', ProductController::class)->except('show')->names('products')
        ->middlewareFor('index', 'authorized:products.admin.view')
        ->middlewareFor(['create', 'store'], 'authorized:products.create')
        ->middlewareFor(['edit', 'update'], 'authorized:products.update')
        ->middlewareFor('destroy', 'authorized:products.delete');

    Route::resource('categories', CategoryController::class)->except('show')->names('categories')
        ->middlewareFor('index', 'authorized:categories.view')
        ->middlewareFor(['create', 'store'], 'authorized:categories.create')
        ->middlewareFor(['edit', 'update'], 'authorized:categories.update')
        ->middlewareFor('destroy', 'authorized:categories.delete');

    Route::resource('users', UserController::class)->names('users')
        ->middlewareFor('index', 'authorized:users.view')
        ->middlewareFor(['create', 'store'], 'authorized:users.create')
        ->middlewareFor(['show', 'edit', 'update'], 'authorized:users.update')
        ->middlewareFor('destroy', 'authorized:users.delete');

    Route::get('/sales', [SaleController::class, 'index'])
        ->middleware('authorized:sales.view_all')->name('sales.index');
});

require __DIR__.'/auth.php';
