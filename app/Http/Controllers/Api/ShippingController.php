<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    /**
     * Cari destinasi menggunakan endpoint RajaOngkir Komerce.
     */
    public function searchDestination(Request $request)
    {
        $request->validate(['search' => 'required|string|min:3']);

        $response = Http::get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $request->search,
            'limit' => 15
        ]);

        if ($response->successful() && isset($response->json()['data'])) {
            return response()->json($response->json()['data']);
        }

        return response()->json([]);
    }

    /**
     * Hitung ongkos kirim menggunakan endpoint RajaOngkir Komerce.
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'courier'     => 'required|string',
        ]);

        $apiKey  = config('services.komerce.api_key');
        $origin  = config('services.komerce.origin_id');
        $cartWeight = 1000; // Asumsi berat 1000 gram. Sebaiknya ambil dari data cart.

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'key'    => $apiKey
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin'      => $origin,
            'destination' => $request->destination,
            'weight'      => $cartWeight,
            'courier'     => $request->courier
        ]);

        return $response->json();
    }
}
