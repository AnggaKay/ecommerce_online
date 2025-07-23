<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware untuk memastikan hanya admin yang bisa akses
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke panel admin.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Statistik utama
        $stats = [
            'total_products' => Product::count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_orders' => Order::count(),
            // FIX: Menggunakan status 'selesai' untuk pendapatan
            'total_revenue' => Order::where('status', 'selesai')->sum('total'),
        ];

        // Pesanan terbaru
        $recentOrders = Order::with(['user'])
            ->latest()
            ->limit(5)
            ->get();

        // Pengguna terbaru
        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->limit(5)
            ->get();

        // Pesan kontak yang belum dibaca
        // $unreadMessages = Contact::where('is_read', false)->count();

        // Data pendapatan untuk grafik
        $monthlyRevenue = $this->getMonthlyRevenue();

        // Produk terlaris
        $topProducts = $this->getTopSellingProducts();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentUsers',
            // 'unreadMessages',
            'monthlyRevenue',
            'topProducts'
        ));
    }

    private function getMonthlyRevenue()
    {
        $months = collect([]);
        $revenue = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M'); // Format bulan dalam Bahasa Indonesia

            // FIX: Menggunakan status 'selesai' untuk pendapatan bulanan
            $monthlyRevenue = Order::where('status', 'selesai')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');

            $months->push($monthName);
            $revenue->push($monthlyRevenue);
        }

        return ['labels' => $months, 'data' => $revenue];
    }

    private function getTopSellingProducts()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }
}
