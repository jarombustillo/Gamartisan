<?php

namespace App\Services\AI;

use App\Models\Buyer;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BehavioralAnalyticsService
{
    /**
     * Get all behavioral analytics data.
     */
    public function getAnalytics()
    {
        return [
            'engagement_metrics' => $this->getEngagementMetrics(),
            'buyer_segments' => $this->getBuyerSegments(),
            'cohort_retention' => $this->getCohortRetention(),
            'conversion_funnel' => $this->getConversionFunnel(),
        ];
    }

    /**
     * Compute engagement scores for all buyers.
     * Score based on: order frequency, review activity, recency.
     */
    public function getEngagementMetrics()
    {
        $buyers = Buyer::where('status', 'active')->get();
        $scores = [];

        foreach ($buyers as $buyer) {
            $orderCount = Order::where('buyerID', $buyer->buyerID)
                ->where('ordStatus', '!=', 'cancelled')
                ->count();

            $reviewCount = Review::where('buyerID', $buyer->buyerID)->count();

            $lastOrder = Order::where('buyerID', $buyer->buyerID)
                ->orderBy('orderDate', 'desc')
                ->first();

            $daysSinceLastOrder = $lastOrder
                ? Carbon::parse($lastOrder->orderDate)->diffInDays(now())
                : 999;

            $totalSpent = Order::where('buyerID', $buyer->buyerID)
                ->where('ordStatus', '!=', 'cancelled')
                ->sum('ordTotalPrice');

            // Engagement Score (0–100)
            $frequencyScore = min($orderCount * 10, 30);         // Max 30
            $reviewScore = min($reviewCount * 5, 20);             // Max 20
            $recencyScore = max(0, 30 - ($daysSinceLastOrder * 0.5)); // Max 30
            $spendingScore = min(($totalSpent / 100) * 2, 20);   // Max 20

            $totalScore = round($frequencyScore + $reviewScore + $recencyScore + $spendingScore);

            $scores[] = [
                'buyer' => $buyer,
                'score' => min($totalScore, 100),
                'order_count' => $orderCount,
                'review_count' => $reviewCount,
                'days_since_last' => $daysSinceLastOrder === 999 ? null : $daysSinceLastOrder,
                'total_spent' => round($totalSpent, 2),
            ];
        }

        // Sort by score descending
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

        // Distribution buckets
        $distribution = [
            'highly_engaged' => count(array_filter($scores, fn($s) => $s['score'] >= 70)),
            'moderately_engaged' => count(array_filter($scores, fn($s) => $s['score'] >= 40 && $s['score'] < 70)),
            'low_engagement' => count(array_filter($scores, fn($s) => $s['score'] >= 10 && $s['score'] < 40)),
            'inactive' => count(array_filter($scores, fn($s) => $s['score'] < 10)),
        ];

        return [
            'scores' => array_slice($scores, 0, 20), // Top 20 for display
            'distribution' => $distribution,
            'avg_score' => count($scores) > 0 ? round(array_sum(array_column($scores, 'score')) / count($scores), 1) : 0,
        ];
    }

    /**
     * Segment buyers by recency/frequency into groups.
     */
    public function getBuyerSegments()
    {
        $buyers = Buyer::where('status', 'active')->get();
        $segments = [
            'new' => [],       // Registered recently, may or may not have ordered
            'active' => [],    // Ordered in last 30 days
            'at_risk' => [],   // Last order 31–90 days ago
            'churned' => [],   // No orders in 90+ days or never ordered
        ];

        foreach ($buyers as $buyer) {
            $lastOrder = Order::where('buyerID', $buyer->buyerID)
                ->orderBy('orderDate', 'desc')
                ->first();

            $orderCount = Order::where('buyerID', $buyer->buyerID)
                ->where('ordStatus', '!=', 'cancelled')
                ->count();

            if ($orderCount === 0) {
                $segments['new'][] = $buyer;
            } elseif ($lastOrder && Carbon::parse($lastOrder->orderDate)->diffInDays(now()) <= 30) {
                $segments['active'][] = $buyer;
            } elseif ($lastOrder && Carbon::parse($lastOrder->orderDate)->diffInDays(now()) <= 90) {
                $segments['at_risk'][] = $buyer;
            } else {
                $segments['churned'][] = $buyer;
            }
        }

        return [
            'new' => ['count' => count($segments['new']), 'label' => 'New Users', 'color' => 'blue'],
            'active' => ['count' => count($segments['active']), 'label' => 'Active Buyers', 'color' => 'green'],
            'at_risk' => ['count' => count($segments['at_risk']), 'label' => 'At Risk', 'color' => 'yellow'],
            'churned' => ['count' => count($segments['churned']), 'label' => 'Churned', 'color' => 'red'],
        ];
    }

    /**
     * Cohort retention: how many buyers from each month's cohort return to buy in subsequent months.
     */
    public function getCohortRetention()
    {
        $cohorts = [];

        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            $cohortStart = now()->subMonths($monthsAgo)->startOfMonth();
            $cohortEnd = now()->subMonths($monthsAgo)->endOfMonth();
            $cohortLabel = $cohortStart->format('M Y');

            // Buyers who made their first order in this cohort month
            $firstTimeBuyers = Order::where('ordStatus', '!=', 'cancelled')
                ->whereBetween('orderDate', [$cohortStart, $cohortEnd])
                ->select('buyerID')
                ->groupBy('buyerID')
                ->havingRaw('MIN(orderDate) >= ?', [$cohortStart])
                ->pluck('buyerID')
                ->toArray();

            $cohortSize = count($firstTimeBuyers);
            if ($cohortSize === 0) {
                $cohorts[] = [
                    'label' => $cohortLabel,
                    'size' => 0,
                    'retention' => [],
                ];
                continue;
            }

            $retention = [];
            for ($followUp = 1; $followUp <= min(5 - $monthsAgo, 5); $followUp++) {
                if ($monthsAgo - $followUp < -1) break;

                $periodStart = $cohortStart->copy()->addMonths($followUp)->startOfMonth();
                $periodEnd = $cohortStart->copy()->addMonths($followUp)->endOfMonth();

                if ($periodEnd->isFuture()) break;

                $returning = Order::whereIn('buyerID', $firstTimeBuyers)
                    ->where('ordStatus', '!=', 'cancelled')
                    ->whereBetween('orderDate', [$periodStart, $periodEnd])
                    ->distinct('buyerID')
                    ->count('buyerID');

                $retention[] = [
                    'month' => $followUp,
                    'label' => $periodStart->format('M'),
                    'returning' => $returning,
                    'rate' => round(($returning / $cohortSize) * 100, 1),
                ];
            }

            $cohorts[] = [
                'label' => $cohortLabel,
                'size' => $cohortSize,
                'retention' => $retention,
            ];
        }

        return $cohorts;
    }

    /**
     * Conversion funnel: cart additions → checkouts → completed orders.
     */
    public function getConversionFunnel()
    {
        // Total registered buyers
        $totalBuyers = Buyer::where('status', 'active')->count();

        // Buyers who added to cart (ever)
        $cartUsers = CartItem::distinct('userID')->count('userID');

        // Buyers who placed orders
        $orderUsers = Order::distinct('buyerID')->count('buyerID');

        // Buyers with completed/delivered orders
        $completedUsers = Order::whereIn('ordStatus', ['delivered', 'shipped'])
            ->distinct('buyerID')
            ->count('buyerID');

        // Buyers who left reviews
        $reviewUsers = Review::distinct('buyerID')->count('buyerID');

        $funnel = [
            ['stage' => 'Registered Users', 'count' => $totalBuyers, 'rate' => 100],
            ['stage' => 'Added to Cart', 'count' => $cartUsers, 'rate' => $totalBuyers > 0 ? round(($cartUsers / $totalBuyers) * 100, 1) : 0],
            ['stage' => 'Placed Orders', 'count' => $orderUsers, 'rate' => $totalBuyers > 0 ? round(($orderUsers / $totalBuyers) * 100, 1) : 0],
            ['stage' => 'Order Fulfilled', 'count' => $completedUsers, 'rate' => $totalBuyers > 0 ? round(($completedUsers / $totalBuyers) * 100, 1) : 0],
            ['stage' => 'Left Reviews', 'count' => $reviewUsers, 'rate' => $totalBuyers > 0 ? round(($reviewUsers / $totalBuyers) * 100, 1) : 0],
        ];

        return $funnel;
    }
}
