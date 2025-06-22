<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Memastikan semua method di controller ini hanya bisa diakses oleh user yang sudah login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil keranjang milik user yang sedang login, beserta item dan relasi produknya.
        $cart = Cart::with('cartItems.product.images')
                    ->where('user_id', Auth::id())
                    ->first();

        return view('cart.index', compact('cart'));
    }

    /**
     * Menambahkan item ke dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah stok mencukupi
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // 1. Cari atau buat keranjang untuk user yang login
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // 2. Cek apakah produk sudah ada di dalam keranjang
        $cartItem = $cart->cartItems()
                         ->where('product_id', $product->id)
                         ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan kuantitasnya
            $cartItem->increment('quantity', $request->quantity);
        } else {
            // Jika belum ada, buat item baru
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->final_price, // Mengambil harga final (setelah diskon)
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Memperbarui kuantitas item di keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);

        // Pastikan user hanya bisa mengupdate keranjangnya sendiri
        if ($cartItem->cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // Cek stok sebelum update
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Kuantitas produk berhasil diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);

        // Pastikan user hanya bisa menghapus dari keranjangnya sendiri
        if ($cartItem->cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
