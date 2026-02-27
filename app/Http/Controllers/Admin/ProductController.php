<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['artist', 'category']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('prodName', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('prodStatus', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('categoryID', $request->category);
        }

        $products = $query->orderBy('productID', 'desc')->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $artists = Artist::all();
        $categories = Category::all();
        return view('admin.products.create', compact('artists', 'categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'artistID' => 'required|exists:ARTISTS,artistID',
            'categoryID' => 'required|exists:CATEGORIES,categoryID',
            'prodName' => 'required|string|max:100',
            'prodDesc' => 'nullable|string',
            'prodPrice' => 'required|numeric|min:0',
            'prodImage' => 'nullable|string|max:255',
            'prodStatus' => 'required|in:available,unavailable',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['artist', 'category', 'reviews.buyer', 'orders.buyer']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $artists = Artist::all();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'artists', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'artistID' => 'required|exists:ARTISTS,artistID',
            'categoryID' => 'required|exists:CATEGORIES,categoryID',
            'prodName' => 'required|string|max:100',
            'prodDesc' => 'nullable|string',
            'prodPrice' => 'required|numeric|min:0',
            'prodImage' => 'nullable|string|max:255',
            'prodStatus' => 'required|in:available,unavailable',
        ]);

        $oldStatus = $product->prodStatus;
        $product->update($validated);

        // Notify artist if product status changed
        if ($oldStatus !== $validated['prodStatus']) {
            Notification::send('product', "Your product '{$product->prodName}' status changed to " . ucfirst($validated['prodStatus']), [
                'artistID' => $product->artistID,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
