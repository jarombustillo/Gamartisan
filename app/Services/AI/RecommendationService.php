<?php

namespace App\Services\AI;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Get personalized product recommendations for a user.
     * Uses collaborative filtering + category affinity + popularity scoring.
     */
    public function getRecommendationsForUser($userId, $userType, $limit = 6)
    {
        // Get products the user has ordered
        $orderedProductIds = Order::where('buyerID', $userId)
            ->pluck('productID')
            ->unique()
            ->toArray();

        if (empty($orderedProductIds)) {
            // Fallback: return popular products for new users
            return $this->getPopularProducts($limit);
        }

        // Get categories and artists from user's past orders
        $userCategories = Product::whereIn('productID', $orderedProductIds)
            ->pluck('categoryID')
            ->unique()
            ->toArray();

        $userArtists = Product::whereIn('productID', $orderedProductIds)
            ->pluck('artistID')
            ->unique()
            ->toArray();

        // Score products the user hasn't ordered yet
        $recommendations = Product::where('prodStatus', 'available')
            ->whereNotIn('productID', $orderedProductIds)
            ->with(['artist', 'category', 'reviews'])
            ->get()
            ->map(function ($product) use ($userCategories, $userArtists, $orderedProductIds) {
                $score = 0;

                // Category affinity (+3 if same category as past purchases)
                if (in_array($product->categoryID, $userCategories)) {
                    $score += 3;
                }

                // Artist affinity (+2 if same artist as past purchases)
                if (in_array($product->artistID, $userArtists)) {
                    $score += 2;
                }

                // Co-purchase score: how many buyers of this product also bought user's products
                $coPurchaseScore = $this->getCoPurchaseScore($product->productID, $orderedProductIds);
                $score += $coPurchaseScore;

                // Popularity score (normalized order count)
                $orderCount = $product->orders()->count();
                $score += min($orderCount * 0.5, 3); // Cap at 3

                // Rating bonus
                $avgRating = $product->reviews->avg('revRating') ?? 0;
                $score += ($avgRating / 5) * 2; // Up to 2 points

                $product->ai_score = round($score, 2);
                return $product;
            })
            ->sortByDesc('ai_score')
            ->take($limit)
            ->values();

        return $recommendations;
    }

    /**
     * Get products similar to a given product.
     * Based on category, artist, price range, and ratings.
     */
    public function getSimilarProducts($productId, $limit = 4)
    {
        $product = Product::find($productId);
        if (!$product) {
            return collect();
        }

        $priceRange = $product->prodPrice * 0.5;

        return Product::where('prodStatus', 'available')
            ->where('productID', '!=', $productId)
            ->with(['artist', 'category', 'reviews'])
            ->get()
            ->map(function ($p) use ($product, $priceRange) {
                $score = 0;

                // Same category (+4)
                if ($p->categoryID === $product->categoryID) {
                    $score += 4;
                }

                // Same artist (+2)
                if ($p->artistID === $product->artistID) {
                    $score += 2;
                }

                // Similar price range (+2)
                if (abs($p->prodPrice - $product->prodPrice) <= $priceRange) {
                    $score += 2;
                }

                // High rating bonus
                $avgRating = $p->reviews->avg('revRating') ?? 0;
                $score += ($avgRating / 5) * 2;

                $p->ai_score = round($score, 2);
                return $p;
            })
            ->sortByDesc('ai_score')
            ->take($limit)
            ->values();
    }

    /**
     * Get products frequently bought together with the given product.
     * Uses co-purchase analysis from order history.
     */
    public function getFrequentlyBoughtTogether($productId, $limit = 4)
    {
        // Find buyers who bought this product
        $buyerIds = Order::where('productID', $productId)
            ->pluck('buyerID')
            ->unique()
            ->toArray();

        if (empty($buyerIds)) {
            return collect();
        }

        // Find what else those buyers bought, ranked by frequency
        return Product::where('prodStatus', 'available')
            ->where('productID', '!=', $productId)
            ->whereHas('orders', function ($q) use ($buyerIds) {
                $q->whereIn('buyerID', $buyerIds);
            })
            ->withCount(['orders' => function ($q) use ($buyerIds) {
                $q->whereIn('buyerID', $buyerIds);
            }])
            ->with(['artist', 'category'])
            ->orderByDesc('orders_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get popular products based on order count and ratings.
     */
    public function getPopularProducts($limit = 6)
    {
        return Product::where('prodStatus', 'available')
            ->withCount('orders')
            ->with(['artist', 'category', 'reviews'])
            ->get()
            ->map(function ($product) {
                $avgRating = $product->reviews->avg('revRating') ?? 0;
                $product->ai_score = round(($product->orders_count * 2) + ($avgRating * 1.5), 2);
                return $product;
            })
            ->sortByDesc('ai_score')
            ->take($limit)
            ->values();
    }

    /**
     * Calculate co-purchase score between a product and a set of products.
     */
    private function getCoPurchaseScore($productId, array $referenceProductIds)
    {
        $buyersOfProduct = Order::where('productID', $productId)
            ->pluck('buyerID')
            ->unique();

        if ($buyersOfProduct->isEmpty()) {
            return 0;
        }

        $overlap = Order::whereIn('buyerID', $buyersOfProduct)
            ->whereIn('productID', $referenceProductIds)
            ->distinct('buyerID')
            ->count('buyerID');

        return min($overlap, 5); // Cap at 5 points
    }
}
