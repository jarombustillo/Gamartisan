<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Donation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show the report generation page.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate Sales Report.
     */
    public function sales(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Order::with(['product', 'buyer', 'artist', 'payment'])
            ->orderBy('orderDate', 'desc');

        if ($dateFrom) {
            $query->whereDate('orderDate', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('orderDate', '<=', $dateTo);
        }

        $orders = $query->get();

        $totalRevenue = $orders->sum('ordTotalPrice');
        $totalPlatformFee = $orders->sum('platformFee');
        $totalSellerEarnings = $orders->sum('sellerAmount');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $statusBreakdown = $orders->groupBy('ordStatus')->map->count();

        return view('admin.reports.sales', compact(
            'orders', 'totalRevenue', 'totalPlatformFee', 'totalSellerEarnings',
            'totalOrders', 'avgOrderValue', 'statusBreakdown', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Generate Donation Report.
     */
    public function donations(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Donation::with(['charity', 'order.buyer'])
            ->orderBy('dateDonated', 'desc');

        if ($dateFrom) {
            $query->whereDate('dateDonated', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('dateDonated', '<=', $dateTo);
        }

        $donations = $query->get();

        $totalDonated = $donations->sum('amountDonated');
        $totalDonations = $donations->count();

        // Group by charity
        $byCharity = $donations->groupBy(function ($d) {
            return $d->charity->charName ?? 'Unknown';
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amountDonated'),
            ];
        })->sortByDesc('total');

        return view('admin.reports.donations', compact(
            'donations', 'totalDonated', 'totalDonations', 'byCharity', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Generate Artist Performance Report.
     */
    public function artistPerformance(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Order::query();

        if ($dateFrom) {
            $query->whereDate('orderDate', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('orderDate', '<=', $dateTo);
        }

        // Get artist performance data
        $artists = $query->select(
                'artistID',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(ordTotalPrice) as total_sales'),
                DB::raw('SUM(sellerAmount) as total_earnings'),
                DB::raw('SUM(ordQuantity) as total_items_sold'),
                DB::raw('AVG(ordTotalPrice) as avg_order_value')
            )
            ->groupBy('artistID')
            ->orderByDesc('total_sales')
            ->get();

        // Load artist details
        foreach ($artists as $artist) {
            $artist->artist = \App\Models\Artist::find($artist->artistID);
            $artist->product_count = Product::where('artistID', $artist->artistID)->count();
        }

        $grandTotalSales = $artists->sum('total_sales');
        $grandTotalOrders = $artists->sum('total_orders');

        return view('admin.reports.artist-performance', compact(
            'artists', 'grandTotalSales', 'grandTotalOrders', 'dateFrom', 'dateTo'
        ));
    }
}
