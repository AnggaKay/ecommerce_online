<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Filter produk berdasarkan kategori jika ada
        $query = Product::query()->with('images', 'category');
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter berdasarkan harga
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Filter berdasarkan rating
        if ($request->has('rating')) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->groupBy('product_id')
                  ->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }
        
        // Sorting
        if ($request->has('sort')) {
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
                    $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->where('is_active', true)->paginate(12);
        $categories = Category::where('is_active', true)->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                        ->with(['images', 'category', 'reviews' => function($query) {
                            $query->where('is_approved', true)
                                  ->orderBy('created_at', 'desc');
                        }])
                        ->firstOrFail();
        
        // Mendapatkan produk terkait dari kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->where('is_active', true)
                                ->with('images')
                                ->take(4)
                                ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }
} 