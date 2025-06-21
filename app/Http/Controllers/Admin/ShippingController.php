<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display a listing of the shipping methods.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $shippingMethods = ShippingMethod::orderBy('name')->paginate(10);
        
        return view('admin.shipping.index', compact('shippingMethods'));
    }
    
    /**
     * Show the form for creating a new shipping method.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.shipping.create');
    }
    
    /**
     * Store a newly created shipping method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:0',
        ]);
        
        ShippingMethod::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil ditambahkan.');
    }
    
    /**
     * Show the form for editing the specified shipping method.
     *
     * @param  \App\Models\ShippingMethod  $shipping
     * @return \Illuminate\View\View
     */
    public function edit(ShippingMethod $shipping)
    {
        return view('admin.shipping.edit', compact('shipping'));
    }
    
    /**
     * Update the specified shipping method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingMethod  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ShippingMethod $shipping)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:0',
        ]);
        
        $shipping->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil diperbarui.');
    }
    
    /**
     * Toggle the active status of the specified shipping method.
     *
     * @param  \App\Models\ShippingMethod  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive(ShippingMethod $shipping)
    {
        $shipping->update([
            'is_active' => !$shipping->is_active,
        ]);
        
        $status = $shipping->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Metode pengiriman berhasil {$status}.");
    }
    
    /**
     * Remove the specified shipping method from storage.
     *
     * @param  \App\Models\ShippingMethod  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ShippingMethod $shipping)
    {
        $shipping->delete();
        
        return redirect()->route('admin.shipping.index')
            ->with('success', 'Metode pengiriman berhasil dihapus.');
    }
} 