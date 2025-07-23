<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }
        return view('checkout.index', compact('cart'));
    }

    public function calculateCost(Request $request)
    {
        $request->validate(['courier' => 'required|string']);
        $courier = strtoupper($request->courier);
        $mockCost = 30000;
        $response = [
            'meta' => ['code' => 200, 'message' => 'OK'],
            'data' => [
                [
                    'code' => strtolower($courier),
                    'name' => "Kurir $courier",
                    'costs' => [
                        [
                            'service' => 'Layanan Reguler',
                            'description' => 'Estimasi pengiriman reguler',
                            'cost' => [['value' => $mockCost, 'etd' => '2-4', 'note' => '']]
                        ]
                    ]
                ]
            ]
        ];
        return response()->json($response);
    }

    public function processOrder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'shipping_courier' => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        $cart = Cart::with('cartItems.product')->where('user_id', auth()->id())->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Sesi checkout berakhir, keranjang Anda kosong.');
        }

        $order = null;

        DB::transaction(function () use ($validated, $cart, &$order) {
            $subtotal = $cart->subtotal;
            $totalAmount = $subtotal + $validated['shipping_cost'];

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'invoice_number' => 'INV-' . strtoupper(Str::random(4)) . time(),
                'status' => 'menunggu_pembayaran',
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'notes' => $validated['notes'],
                'shipping_courier' => $validated['shipping_courier'],
                'shipping_service' => $validated['shipping_service'],
                'subtotal' => $subtotal,
                'shipping_cost' => $validated['shipping_cost'],
                'total' => $totalAmount,
            ]);

            // Pindahkan item dari keranjang ke 'order_items'
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price, // <-- PERUBAHAN KRUSIAL DI SINI
                ]);
            }

            $cart->cartItems()->delete();
            $cart->delete();
        });

        if ($order) {
            return redirect()->route('orders.payment', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        }

        return redirect()->back()->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
    }
}
