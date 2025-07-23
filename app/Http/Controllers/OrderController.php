<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan order ini milik user yang sedang login
        abort_if($order->user_id !== Auth::id(), 403);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the payment instruction page for a specific order.
     */
    public function payment(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'menunggu_pembayaran', 404, 'Halaman tidak valid untuk pesanan ini.');

        return view('orders.payment', compact('order'));
    }

    /**
     * Handle the submission of payment proof.
     */
    public function submitPaymentProof(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $order = Order::findOrFail($request->order_id);
        abort_if($order->user_id !== Auth::id(), 403);

        if ($request->hasFile('payment_proof')) {
            // Hapus bukti lama jika ada
            if ($order->payment_proof) {
                \Storage::disk('public')->delete($order->payment_proof);
            }

            // Simpan file baru
            $path = $request->file('payment_proof')->store('proofs', 'public');

            $order->update([
                'payment_proof' => $path,
                'status' => 'menunggu_validasi'
            ]);

            return redirect()->route('orders.show', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan Anda akan segera kami proses.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }
}
