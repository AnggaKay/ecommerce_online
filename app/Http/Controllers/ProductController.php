<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman daftar produk dengan fungsionalitas filter, sorting, dan pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Memulai query dasar dengan eager loading untuk efisiensi
        $query = Product::query()->with('images', 'category');

        // --- FILTERING ---
        // Filter produk berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan harga minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filter berdasarkan harga maksimum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter berdasarkan rating (hanya jika ada relasi 'reviews')
        if ($request->filled('rating')) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->select(DB::raw('AVG(rating) as average_rating'))
                  ->groupBy('product_id')
                  ->having('average_rating', '>=', $request->rating);
            });
        }

        // --- SORTING ---
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popularity':
                    // Asumsi popularitas berdasarkan jumlah pesanan (jika ada relasi 'orderItems')
                    $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                    break;
                default:
                    $query->latest(); // Default sorting adalah yang terbaru
            }
        } else {
            $query->latest();
        }

        // Ambil hanya produk yang aktif dan paginasi hasilnya
        $products = $query->where('is_active', true)->paginate(12);

        // Ambil semua kategori aktif untuk ditampilkan di sidebar filter
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan halaman detail untuk satu produk spesifik.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // Cari produk berdasarkan slug, jika tidak ada akan menampilkan error 404
        // Eager load relasi yang dibutuhkan
        $product = Product::where('slug', $slug)
                        ->with(['images', 'category', 'reviews' => function($query) {
                            $query->where('is_approved', true) // Hanya tampilkan review yang sudah disetujui
                                  ->with('user') // Ambil juga data user yang memberi review
                                  ->latest();
                        }])
                        ->firstOrFail();

        // Mengambil produk terkait dari kategori yang sama (kecuali produk itu sendiri)
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id) // Bukan produk ini
                                ->where('is_active', true)
                                ->with('images') // Ambil gambar untuk ditampilkan di card
                                ->inRandomOrder() // Tampilkan secara acak
                                ->take(4) // Ambil 4 produk saja
                                ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
