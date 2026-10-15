<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/masukan', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/masukan', [FeedbackController::class, 'store'])
    ->middleware('throttle:5,1') // maksimal 5 kiriman per menit per IP/user
    ->name('feedback.store');

Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/{product}', [CartController::class, 'store'])->name('store');
    Route::patch('/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/{product}', [CartController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Pelanggan (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');

    // Breeze: halaman profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
| Breeze mengarahkan user ke route "dashboard" setelah login.
| Di sini kita arahkan sesuai peran: admin ke panel, pelanggan ke beranda.
*/
Route::get('/dashboard', fn () => auth()->user()->isAdmin()
    ? redirect()->route('admin.dashboard')
    : redirect()->route('home'))
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin (login + role admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');

    Route::resource('feedback', Admin\FeedbackController::class)->only(['index', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
