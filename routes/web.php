<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AddressController; // <--- DITAMBAHKAN

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// OTP Verification Routes
Route::get('/otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// User Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // ========== BLOK ROUTE ALAMAT DITAMBAHKAN DI SINI ==========
    Route::get('/alamat', [AddressController::class, 'index'])->name('alamat');
    Route::post('/alamat', [AddressController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{address}', [AddressController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{address}', [AddressController::class, 'destroy'])->name('alamat.destroy');
    Route::post('/alamat/{address}/set-default', [AddressController::class, 'setDefault'])->name('alamat.setDefault');
    // ==========================================================
});

// Category Routes
Route::get('/categories', function() {
    return view('categories.index');
})->name('categories.index');

Route::get('/about', [PageController::class, 'about'])->name('about.index');


Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');



// Route lainnya akan ditambahkan di sini
