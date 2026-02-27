<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Artist;
use App\Models\Buyer;
use App\Models\Charity;
use App\Models\Donation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get current admin
        $admin = Admin::find(session('admin_id'));

        // Statistics
        $stats = [
            'total_revenue' => Payment::where('payStatus', 'completed')->sum('payAmount') ?? 0,
            'total_orders' => Order::count(),
            'total_buyers' => Buyer::count(),
            'total_artists' => Artist::count(),
            'total_products' => Product::count(),
            'total_charities' => Charity::count(),
            'pending_orders' => Order::where('ordStatus', 'pending')->count(),
            'total_donations' => Donation::sum('amountDonated') ?? 0,
            'platform_earnings' => Order::sum('platformFee') ?? 0,
        ];

        // Recent orders
        $recentOrders = Order::with(['buyer', 'product', 'artist'])
            ->orderBy('orderDate', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = Payment::where('payStatus', 'completed')
            ->where('datePaid', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(datePaid) as month'),
                DB::raw('YEAR(datePaid) as year'),
                DB::raw('SUM(payAmount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Order status distribution
        $ordersByStatus = Order::select('ordStatus', DB::raw('count(*) as count'))
            ->groupBy('ordStatus')
            ->get()
            ->pluck('count', 'ordStatus')
            ->toArray();

        // Top selling products
        $topProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'admin',
            'stats',
            'recentOrders',
            'monthlyRevenue',
            'ordersByStatus',
            'topProducts'
        ));
    }
}
