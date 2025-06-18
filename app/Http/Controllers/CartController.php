<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cart.index');
    }

    /**
     * Add item to cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        // Logic to add item to cart will be implemented here
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update cart item quantity
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Logic to update cart item quantity will be implemented here
        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui');
    }

    /**
     * Remove item from cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        // Logic to remove item from cart will be implemented here
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
