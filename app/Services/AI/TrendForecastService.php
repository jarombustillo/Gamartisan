<?php

namespace App\Services\AI;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrendForecastService
{
    /**
     * Get category trends — rising and declining based on order velocity changes.
     */
    public function getCategoryTrends()
    {
        $categories = Category::with('products')->get();
        $trends = [];

        foreach ($categories as $category) {
            $productIds = $category->products->pluck('productID')->toArray();
            if (empty($productIds)) continue;

            // Orders in current 30 days
            $currentOrders = Order::whereIn('productID', $productIds)
                ->where('ordStatus', '!=', 'cancelled')
                ->where('orderDate', '>=', now()->subDays(30))
                ->count();

            // Orders in previous 30 days
            $previousOrders = Order::whereIn('productID', $productIds)
                ->where('ordStatus', '!=', 'cancelled')
                ->whereBetween('orderDate', [now()->subDays(60), now()->subDays(30)])
                ->count();

            // Current revenue
            $currentRevenue = Order::whereIn('productID', $productIds)
                ->where('ordStatus', '!=', 'cancelled')
                ->where('orderDate', '>=', now()->subDays(30))
                ->sum('ordTotalPrice');

            // Previous revenue
            $previousRevenue = Order::whereIn('productID', $productIds)
                ->where('ordStatus', '!=', 'cancelled')
                ->whereBetween('orderDate', [now()->subDays(60), now()->subDays(30)])
                ->sum('ordTotalPrice');

            $growthRate = $previousOrders > 0
                ? round((($currentOrders - $previousOrders) / $previousOrders) * 100, 1)
                : ($currentOrders > 0 ? 100 : 0);

            $revenueGrowth = $previousRevenue > 0
                ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
                : ($currentRevenue > 0 ? 100 : 0);

            $trend = 'stable';
            if ($growthRate > 15) $trend = 'rising';
            elseif ($growthRate < -15) $trend = 'declining';

            $trends[] = [
                'category' => $category,
                'current_orders' => $currentOrders,
                'previous_orders' => $previousOrders,
                'growth_rate' => $growthRate,
                'current_revenue' => round($currentRevenue, 2),
                'revenue_growth' => $revenueGrowth,
                'trend' => $trend,
                'product_count' => $category->products->count(),
            ];
        }

        // Sort by growth rate descending
        usort($trends, fn($a, $b) => $b['growth_rate'] <=> $a['growth_rate']);

        return $trends;
    }

    /**
     * Get emerging products — high growth rate in recent orders.
     */
    public function getEmergingProducts($limit = 10)
    {
        $products = Product::where('prodStatus', 'available')
            ->with(['artist', 'category'])
            ->get();

        $emerging = [];
        foreach ($products as $product) {
            $currentOrders = Order::where('productID', $product->productID)
                ->where('ordStatus', '!=', 'cancelled')
                ->where('orderDate', '>=', now()->subDays(30))
                ->count();

            $previousOrders = Order::where('productID', $product->productID)
                ->where('ordStatus', '!=', 'cancelled')
                ->whereBetween('orderDate', [now()->subDays(60), now()->subDays(30)])
                ->count();

            $growthRate = $previousOrders > 0
                ? round((($currentOrders - $previousOrders) / $previousOrders) * 100, 1)
                : ($currentOrders > 0 ? 100 : 0);

            // Only include products with some recent activity
            if ($currentOrders > 0) {
                $emerging[] = [
                    'product' => $product,
                    'current_orders' => $currentOrders,
                    'previous_orders' => $previousOrders,
                    'growth_rate' => $growthRate,
                ];
            }
        }

        usort($emerging, fn($a, $b) => $b['growth_rate'] <=> $a['growth_rate']);
        return array_slice($emerging, 0, $limit);
    }

    /**
     * Get seasonal patterns from historical order data.
     */
    public function getSeasonalPatterns()
    {
        $monthly = Order::where('ordStatus', '!=', 'cancelled')
            ->select(
                DB::raw('MONTH(orderDate) as month'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(ordTotalPrice) as revenue'),
                DB::raw('AVG(ordTotalPrice) as avg_order_value')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $months = ['', 'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'];
                return [
                    'month' => $months[$row->month] ?? 'Unknown',
                    'month_short' => substr($months[$row->month] ?? '', 0, 3),
                    'month_num' => $row->month,
                    'orders' => $row->orders,
                    'revenue' => round($row->revenue, 2),
                    'avg_order_value' => round($row->avg_order_value, 2),
                ];
            });

        // Determine peak and low seasons
        $maxOrders = $monthly->max('orders') ?: 1;
        $monthly = $monthly->map(function ($item) use ($maxOrders) {
            $intensity = round(($item['orders'] / $maxOrders) * 100);
            $item['intensity'] = $intensity;
            $item['season'] = $intensity >= 75 ? 'peak' : ($intensity >= 40 ? 'moderate' : 'low');
            return $item;
        });

        return $monthly;
    }

    /**
     * Get demand forecast per category for upcoming months.
     */
    public function getDemandForecast()
    {
        $categories = Category::all();
        $forecasts = [];

        foreach ($categories as $category) {
            $productIds = Product::where('categoryID', $category->categoryID)
                ->pluck('productID')
                ->toArray();

            if (empty($productIds)) continue;

            // Collect 6 months of data
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $start = now()->subMonths($i)->startOfMonth();
                $end = now()->subMonths($i)->endOfMonth();

                $count = Order::whereIn('productID', $productIds)
                    ->where('ordStatus', '!=', 'cancelled')
                    ->whereBetween('orderDate', [$start, $end])
                    ->count();

                $monthlyData[] = [
                    'label' => $start->format('M Y'),
                    'orders' => $count,
                    'is_forecast' => false,
                ];
            }

            // Simple moving average for 3-month forecast
            $values = array_column($monthlyData, 'orders');
            $recentAvg = count($values) >= 3
                ? array_sum(array_slice($values, -3)) / 3
                : (count($values) > 0 ? array_sum($values) / count($values) : 0);

            // Trend adjustment
            $trend = 0;
            if (count($values) >= 2) {
                $recent = array_slice($values, -2);
                $trend = ($recent[1] - $recent[0]) * 0.3;
            }

            $forecastData = [];
            for ($m = 1; $m <= 3; $m++) {
                $predicted = max(0, round($recentAvg + ($trend * $m)));
                $forecastData[] = [
                    'label' => now()->addMonths($m)->format('M Y'),
                    'orders' => $predicted,
                    'is_forecast' => true,
                ];
            }

            $forecasts[] = [
                'category' => $category,
                'historical' => $monthlyData,
                'forecast' => $forecastData,
            ];
        }

        return $forecasts;
    }
}
