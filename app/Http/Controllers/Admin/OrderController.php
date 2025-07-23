<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user'])->latest();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // FIX: Filter berdasarkan status yang sudah disesuaikan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // FIX: Validasi menggunakan status yang sudah disesuaikan
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,menunggu_validasi,diproses,dikirim,selesai,dibatalkan',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        // Anda bisa menambahkan logika untuk generate PDF di sini
        return view('admin.orders.invoice', compact('order'));
    }

    public function destroy(Order $order)
    {
        // FIX: Hanya pesanan dengan status 'dibatalkan' yang bisa dihapus
        if ($order->status !== 'dibatalkan') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang dapat dihapus.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
