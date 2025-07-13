<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- INI BARIS KUNCI YANG PERLU DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kode View Composer untuk menghitung item keranjang
        View::composer('*', function ($view) {
            $cartItemCount = 0;
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->with('cartItems')->first();
                if ($cart) {
                    // Menjumlahkan total kuantitas dari semua item di keranjang
                    $cartItemCount = $cart->cartItems->sum('quantity');
                }
            }
            // Bagikan variabel $cartItemCount ke semua view
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}
