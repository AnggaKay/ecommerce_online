<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $payments = Payment::with(['order.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.payments.index', compact('payments'));
    }
    
    /**
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View
     */
    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.items.product']);
        
        return view('admin.payments.show', compact('payment'));
    }
    
    /**
     * Update the status of a payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);
        
        $payment->update([
            'status' => $request->status,
        ]);
        
        // If payment is completed, update the order status to processing
        if ($request->status === 'completed' && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'processing']);
        }
        
        // If payment is failed, update the order status to cancelled
        if ($request->status === 'failed' && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'cancelled']);
        }
        
        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }
    
    /**
     * Remove the specified payment from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Payment $payment)
    {
        // Check if the payment can be deleted
        if ($payment->status !== 'failed') {
            return back()->with('error', 'Hanya pembayaran yang gagal yang dapat dihapus.');
        }
        
        $payment->delete();
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
} 