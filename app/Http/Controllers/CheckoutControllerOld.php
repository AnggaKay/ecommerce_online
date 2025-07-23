<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart; // Ganti dengan model Cart Anda
use App\Models\Order; // Tambahkan model Order

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }
        return view('checkout.index', compact('cart'));
    }

    public function searchDestination(Request $request)
    {
        $request->validate(['search' => 'required|string|min:3']);

        $apiUrl = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';

        // TAMBAHKAN ->withoutVerifying() DI SINI
        $response = Http::withoutVerifying()->get($apiUrl, [
            'search' => $request->search,
            'limit' => 10,
        ]);

        if ($response->successful() && $response->json()['meta']['code'] === 200) {
            return response()->json($response->json()['data']);
        }
        return response()->json([]);
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'courier'     => 'required|string',
        ]);

        // TAMBAHKAN ->withoutVerifying() DI SINI
        $response = Http::withoutVerifying()->withHeaders([
            'key' => config('services.komerce.api_key')
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin'      => config('services.komerce.origin_id'),
            'destination' => $request->destination,
            'weight'      => 1000,
            'courier'     => $request->courier
        ]);

        if ($response->successful() && $response->json()['meta']['code'] === 200) {
            return response()->json($response->json());
        }
        return response()->json(['data' => []], 400);
    }

    /**
     * Method untuk memproses form checkout.
     * Ini adalah kerangka dasar yang bisa Anda kembangkan.
     */
    public function processOrder(Request $request)
    {
        // 1. Validasi data yang disubmit
        $request->validate([
            'destination_id' => 'required',
            'shipping_cost' => 'required|numeric',
            'shipping_service' => 'required|string',
        ]);

        // 2. Logika untuk menyimpan order ke database...
        // ... (misalnya, membuat record di tabel `orders` dan `order_items`)

        // 3. Redirect ke halaman sukses atau detail order
        // return redirect()->route('orders.show', $newOrder->id)->with('success', 'Pesanan berhasil dibuat!');

        // Untuk sekarang, kita hanya akan dump data request
        dd($request->all());
    }
}
