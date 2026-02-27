<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(Request $request, Product $product)
    {
        $buyerID = session('user_id');
        $userType = session('user_type');

        if (!in_array($userType, ['buyer', 'artist'])) {
            abort(403, 'Only buyers and artists can write reviews.');
        }

        $validated = $request->validate([
            'revRating' => 'required|integer|min:1|max:5',
            'revText' => 'nullable|string|max:1000',
            'orderID' => 'required|exists:orders,orderID',
        ]);

        // Verify the buyer owns this order and it's delivered
        $order = Order::where('orderID', $validated['orderID'])
            ->where('buyerID', $buyerID)
            ->where('productID', $product->productID)
            ->where('ordStatus', 'delivered')
            ->first();

        if (!$order) {
            return back()->with('error', 'You can only review delivered orders.');
        }

        // Check if already reviewed this order
        $existing = Review::where('orderID', $validated['orderID'])
            ->where('buyerID', $buyerID)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'productID' => $product->productID,
            'buyerID' => $buyerID,
            'orderID' => $validated['orderID'],
            'revRating' => $validated['revRating'],
            'revText' => $validated['revText'],
            'datePosted' => now(),
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
