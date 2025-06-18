<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil kategori untuk ditampilkan di halaman utama
        $categories = Category::take(6)->get();
        
        // Mengambil produk unggulan untuk ditampilkan di halaman utama
        $featuredProducts = Product::where('is_featured', true)
            ->orWhere('discount_price', '>', 0)
            ->with('images')
            ->take(8)
            ->get();
        
        return view('home', compact('categories', 'featuredProducts'));
    }
}
