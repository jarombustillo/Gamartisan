<?php

namespace App\Services\AI;

use App\Models\Artist;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesInsightService
{
    /**
     * Get comprehensive sales insights for an artist.
     */
    public function getInsightsForArtist($artistId)
    {
        $artist = Artist::find($artistId);

        return [
            'revenue_forecast' => $this->getRevenueForecast($artistId),
            'product_performance' => $this->getProductPerformance($artistId),
            'pricing_suggestions' => $this->getPricingSuggestions($artistId),
            'peak_periods' => $this->getPeakPeriods($artistId),
            'summary_stats' => $this->getSummaryStats($artistId),
        ];
    }

    /**
     * Forecast revenue for the next 3 months using linear regression on monthly data.
     */
    public function getRevenueForecast($artistId)
    {
        $monthlyRevenue = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->where('orderDate', '>=', now()->subMonths(12))
            ->select(
                DB::raw('MONTH(orderDate) as month'),
                DB::raw('YEAR(orderDate) as year'),
                DB::raw('SUM(sellerAmount) as total'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $historical = [];
        $values = [];
        foreach ($monthlyRevenue as $i => $row) {
            $label = Carbon::createFromDate($row->year, $row->month, 1)->format('M Y');
            $historical[] = [
                'label' => $label,
                'revenue' => (float) $row->total,
                'orders' => (int) $row->order_count,
            ];
            $values[] = (float) $row->total;
        }

        // Simple linear regression for forecasting
        $forecast = [];
        if (count($values) >= 2) {
            $n = count($values);
            $sumX = 0; $sumY = 0; $sumXY = 0; $sumX2 = 0;
            for ($i = 0; $i < $n; $i++) {
                $sumX += $i;
                $sumY += $values[$i];
                $sumXY += $i * $values[$i];
                $sumX2 += $i * $i;
            }
            $slope = ($n * $sumXY - $sumX * $sumY) / max(($n * $sumX2 - $sumX * $sumX), 1);
            $intercept = ($sumY - $slope * $sumX) / $n;

            for ($m = 1; $m <= 3; $m++) {
                $futureMonth = now()->addMonths($m);
                $predicted = max(0, round($intercept + $slope * ($n + $m - 1), 2));
                $forecast[] = [
                    'label' => $futureMonth->format('M Y'),
                    'revenue' => $predicted,
                    'is_forecast' => true,
                ];
            }
        } else {
            // Not enough data — estimate flat
            $avg = count($values) > 0 ? array_sum($values) / count($values) : 0;
            for ($m = 1; $m <= 3; $m++) {
                $futureMonth = now()->addMonths($m);
                $forecast[] = [
                    'label' => $futureMonth->format('M Y'),
                    'revenue' => round($avg, 2),
                    'is_forecast' => true,
                ];
            }
        }

        return [
            'historical' => $historical,
            'forecast' => $forecast,
        ];
    }

    /**
     * Get best and worst performing products for the artist.
     */
    public function getProductPerformance($artistId)
    {
        $products = Product::where('artistID', $artistId)
            ->withCount('orders')
            ->with('reviews')
            ->get()
            ->map(function ($product) {
                $revenue = Order::where('productID', $product->productID)
                    ->where('ordStatus', '!=', 'cancelled')
                    ->sum('sellerAmount');
                $avgRating = $product->reviews->avg('revRating') ?? 0;

                $product->total_revenue = (float) $revenue;
                $product->avg_rating = round($avgRating, 1);
                $product->performance_score = round(
                    ($product->orders_count * 3) + ($avgRating * 2) + ($revenue / max($product->prodPrice, 1)),
                    2
                );
                return $product;
            })
            ->sortByDesc('performance_score');

        return [
            'best' => $products->take(5)->values(),
            'worst' => $products->sortBy('performance_score')->take(5)->values(),
        ];
    }

    /**
     * Get pricing suggestions based on category averages and performance.
     */
    public function getPricingSuggestions($artistId)
    {
        $products = Product::where('artistID', $artistId)
            ->with(['category', 'reviews'])
            ->get();

        $suggestions = [];
        foreach ($products as $product) {
            // Get average price in the same category
            $categoryAvg = Product::where('categoryID', $product->categoryID)
                ->where('prodStatus', 'available')
                ->avg('prodPrice') ?? 0;

            $avgRating = $product->reviews->avg('revRating') ?? 0;
            $orderCount = $product->orders()->count();

            $suggestion = null;
            $suggestedPrice = null;

            if ($product->prodPrice > $categoryAvg * 1.3 && $orderCount < 3) {
                // Price is high, sales are low → suggest lowering
                $suggestedPrice = round($categoryAvg * 1.1, 2);
                $suggestion = 'Consider lowering — priced above category average with low sales';
            } elseif ($product->prodPrice < $categoryAvg * 0.7 && $avgRating >= 4) {
                // Price is low but ratings are high → suggest raising
                $suggestedPrice = round($categoryAvg * 0.95, 2);
                $suggestion = 'Consider raising — high ratings suggest buyers value this product';
            } elseif ($avgRating >= 4.5 && $orderCount >= 5) {
                // Premium product
                $suggestedPrice = round($product->prodPrice * 1.15, 2);
                $suggestion = 'Strong performer — premium pricing may be justified';
            }

            if ($suggestion) {
                $suggestions[] = [
                    'product' => $product,
                    'current_price' => $product->prodPrice,
                    'category_avg' => round($categoryAvg, 2),
                    'suggested_price' => $suggestedPrice,
                    'reason' => $suggestion,
                    'avg_rating' => $avgRating,
                    'order_count' => $orderCount,
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Analyze peak sales periods (day of week and monthly patterns).
     */
    public function getPeakPeriods($artistId)
    {
        // Day of week analysis
        $dayOfWeek = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->select(
                DB::raw('DAYOFWEEK(orderDate) as day'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(sellerAmount) as revenue')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(function ($row) {
                $days = ['', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return [
                    'day' => $days[$row->day] ?? 'Unknown',
                    'day_num' => $row->day,
                    'orders' => $row->count,
                    'revenue' => (float) $row->revenue,
                ];
            });

        // Monthly pattern
        $monthly = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->select(
                DB::raw('MONTH(orderDate) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(sellerAmount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return [
                    'month' => $months[$row->month] ?? 'Unknown',
                    'month_num' => $row->month,
                    'orders' => $row->count,
                    'revenue' => (float) $row->revenue,
                ];
            });

        return [
            'by_day' => $dayOfWeek,
            'by_month' => $monthly,
        ];
    }

    /**
     * Get summary statistics.
     */
    private function getSummaryStats($artistId)
    {
        $totalRevenue = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->sum('sellerAmount');

        $last30Revenue = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->where('orderDate', '>=', now()->subDays(30))
            ->sum('sellerAmount');

        $prev30Revenue = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->whereBetween('orderDate', [now()->subDays(60), now()->subDays(30)])
            ->sum('sellerAmount');

        $growthRate = $prev30Revenue > 0
            ? round((($last30Revenue - $prev30Revenue) / $prev30Revenue) * 100, 1)
            : ($last30Revenue > 0 ? 100 : 0);

        $avgOrderValue = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->avg('sellerAmount') ?? 0;

        $repeatBuyerCount = Order::where('artistID', $artistId)
            ->where('ordStatus', '!=', 'cancelled')
            ->select('buyerID')
            ->groupBy('buyerID')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        return [
            'total_revenue' => round($totalRevenue, 2),
            'last_30_revenue' => round($last30Revenue, 2),
            'growth_rate' => $growthRate,
            'avg_order_value' => round($avgOrderValue, 2),
            'repeat_buyers' => $repeatBuyerCount,
        ];
    }
}
