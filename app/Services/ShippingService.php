<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // Pastikan variabel ini ada di file .env Anda
        $this->apiKey = env('KOMERCE_API_KEY');
        $this->baseUrl = env('KOMERCE_BASE_URL');
    }

    /**
     * Mencari tujuan pengiriman domestik berdasarkan nama.
     */
    public function searchDomesticDestination(string $searchTerm)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->baseUrl . '/destination/domestic-destination', [
            'search' => $searchTerm,
            'limit' => 10,
        ]);

        if ($response->successful() && isset($response->json()['data'])) {
            return $response->json()['data'];
        }

        Log::error('Komerce API (Destination) Error: ' . $response->body());
        return null;
    }

    /**
     * Menghitung ongkos kirim.
     * CATATAN: Endpoint dan parameter di bawah ini adalah ASUMSI.
     * Anda perlu menyesuaikannya dengan dokumentasi API Komerce untuk kalkulasi ongkir.
     */
    public function calculateCost(string $originId, string $destinationId, int $weight, string $courier)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . '/shipping/cost', [ // <-- PASTIKAN ENDPOINT INI BENAR
            'origin' => $originId,
            'destination' => $destinationId,
            'weight' => $weight,
            'courier' => strtolower($courier),
        ]);

        // Sesuaikan juga key response-nya jika berbeda
        if ($response->successful() && isset($response->json()['data'])) {
            return $response->json()['data'];
        }

        Log::error('Komerce API (Cost) Error: ' . $response->body());
        return null;
    }
}
