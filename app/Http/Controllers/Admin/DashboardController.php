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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke panel admin.');
            }
            
            return $next($request);
        });
    }
    
    /**
     * Display the admin dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for statistics
        $stats = [
            'total_products' => Product::count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('order_status', 'completed')->sum('total'),
        ];
        
        // Get recent orders
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent users
        $recentUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get unread contact messages
        $unreadMessages = Contact::where('status', 'unread')
            ->count();
            
        // Get monthly revenue data for chart
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        // Get top selling products
        $topProducts = $this->getTopSellingProducts();
        
        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentUsers',
            'unreadMessages',
            'monthlyRevenue',
            'topProducts'
        ));
    }
    
    /**
     * Get monthly revenue data for the last 6 months
     *
     * @return array
     */
    private function getMonthlyRevenue()
    {
        $months = collect([]);
        $revenue = collect([]);
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            
            $monthlyRevenue = Order::where('order_status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
            
            $months->push($monthName);
            $revenue->push($monthlyRevenue);
        }
        
        return [
            'labels' => $months,
            'data' => $revenue
        ];
    }
    
    /**
     * Get top selling products
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTopSellingProducts()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.price', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }
} 