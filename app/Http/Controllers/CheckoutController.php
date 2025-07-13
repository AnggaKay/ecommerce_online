<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Pastikan hanya user yang login yang bisa mengakses.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with('cartItems.product')->where('user_id', $user->id)->first();
        $addresses = $user->addresses;

        // Redirect jika keranjang kosong
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        return view('checkout.index', compact('cart', 'addresses'));
    }

    /**
     * Memproses pesanan untuk dilanjutkan ke payment gateway.
     */
    public function processOrder(Request $request)
    {
        // 1. Validasi semua input dari form checkout
        $request->validate([
            'destination_id' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'shipping_courier' => 'required|string',
            // Tambahkan validasi untuk alamat dan data lainnya jika perlu
        ]);

        // 2. Logika untuk membuat order baru di database dengan status 'pending'
        // ... (Contoh: Order::create([...])) ...

        // 3. Kosongkan keranjang belanja setelah order dibuat
        // ... (Cart::where('user_id', Auth::id())->delete();) ...

        // 4. Panggil service payment gateway (e.g., Midtrans) dan dapatkan token/URL pembayaran
        // ...

        // 5. Redirect ke halaman pembayaran
        // return redirect()->to($paymentUrl);

        return redirect()->route('home')->with('success', 'Pesanan berhasil dibuat dan sedang menunggu pembayaran!');
    }
}
