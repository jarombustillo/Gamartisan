<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of all reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['product', 'buyer', 'order'])->orderBy('datePosted', 'desc');

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('revRating', $request->rating);
        }

        // Search by product name or buyer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q2) use ($search) {
                    $q2->where('prodName', 'like', "%{$search}%");
                })->orWhereHas('buyer', function ($q2) use ($search) {
                    $q2->where('firstName', 'like', "%{$search}%")
                       ->orWhere('lastName', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
