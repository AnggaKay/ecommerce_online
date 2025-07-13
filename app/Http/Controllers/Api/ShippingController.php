<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Endpoint untuk pencarian tujuan pengiriman.
     */
    public function searchDestination(Request $request)
    {
        $request->validate(['search' => 'required|string|min:3']);
        $destinations = $this->shippingService->searchDomesticDestination($request->search);
        return response()->json($destinations);
    }

    /**
     * Endpoint untuk kalkulasi ongkos kirim.
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string',
            'courier' => 'required|string|in:jne,tiki,pos',
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->total_weight <= 0) {
            return response()->json(['error' => 'Keranjang tidak ditemukan atau berat produk belum diatur.'], 404);
        }

        try {
            $originId = config('services.komerce.origin_id', env('KOMERCE_ORIGIN_ID'));

            $cost = $this->shippingService->calculateCost(
                $originId,
                $validated['destination'],
                $cart->total_weight,
                $validated['courier']
            );

            if (is_null($cost)) {
                return response()->json(['error' => 'Layanan pengiriman tidak tersedia.'], 404);
            }

            return response()->json($cost);
        } catch (\Exception $e) {
            Log::error('Shipping Calculation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghitung ongkos kirim.'], 500);
        }
    }
}
