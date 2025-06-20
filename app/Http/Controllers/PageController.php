<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman Tentang Kami (About Us).
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // Cukup kembalikan view 'about'.
        // Pastikan file view ada di resources/views/about.blade.php
        return view('about');
    }

    // Mungkin ada method lain di sini untuk halaman statis lainnya
}
