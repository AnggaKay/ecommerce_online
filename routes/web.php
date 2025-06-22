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
use App\Http\Controllers\AddressController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\ShippingController as AdminShippingController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\PageController as AdminPageController;

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



// User Routes (Protected)
Route::middleware(['auth'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // ========== BLOK ROUTE ALAMAT ==========
    Route::get('/alamat', [AddressController::class, 'index'])->name('alamat');
    Route::post('/alamat', [AddressController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{address}', [AddressController::class, 'update'])->name('alamat.update');
    Route::delete('/alamat/{address}', [AddressController::class, 'destroy'])->name('alamat.destroy');
    Route::post('/alamat/{address}/set-default', [AddressController::class, 'setDefault'])->name('alamat.setDefault');
    // ======================================
});

// Category Routes
Route::get('/categories', function() {
    return view('categories.index');
})->name('categories.index');

Route::get('/about', [PageController::class, 'about'])->name('about.index');


Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/images/{image}/set-primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.set-primary');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');



    // Categories
    Route::resource('categories', AdminCategoryController::class);
    Route::post('categories/{category}/toggle-active', [AdminCategoryController::class, 'toggleActive'])->name('categories.toggle-active');

    // Orders
    Route::resource('orders', AdminOrderController::class);
    Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/invoice', [AdminOrderController::class, 'generateInvoice'])->name('orders.invoice');

    // Payments
    Route::resource('payments', AdminPaymentController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('payments/{payment}/update-status', [AdminPaymentController::class, 'updateStatus'])->name('payments.update-status');

    // Users
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('users/{user}/change-role', [AdminUserController::class, 'changeRole'])->name('users.change-role');

    // Contacts
    Route::resource('contacts', AdminContactController::class)->except(['create', 'store']);
    Route::post('contacts/{contact}/mark-as-read', [AdminContactController::class, 'markAsRead'])->name('contacts.mark-as-read');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);
    Route::post('coupons/{coupon}/toggle-active', [AdminCouponController::class, 'toggleActive'])->name('coupons.toggle-active');

    // Banners
    Route::resource('banners', AdminBannerController::class);
    Route::post('banners/{banner}/toggle-active', [AdminBannerController::class, 'toggleActive'])->name('banners.toggle-active');
    Route::post('banners/update-order', [AdminBannerController::class, 'updateOrder'])->name('banners.update-order');

    // Pages
    Route::resource('pages', AdminPageController::class);
    Route::post('pages/{page}/toggle-active', [AdminPageController::class, 'toggleActive'])->name('pages.toggle-active');

    // Shipping
    Route::resource('shipping', AdminShippingController::class);
    Route::post('shipping/{shipping}/toggle-active', [AdminShippingController::class, 'toggleActive'])->name('shipping.toggle-active');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Admin Profile
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});