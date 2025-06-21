<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);
        
        $order->update([
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
    
    /**
     * Generate invoice for an order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        // Here you would typically use a PDF generation library like DomPDF
        // For now, we'll just return a view
        return view('admin.orders.invoice', compact('order'));
    }
    
    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        // Check if the order can be deleted
        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang dapat dihapus.');
        }
        
        // Delete the order
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
} 