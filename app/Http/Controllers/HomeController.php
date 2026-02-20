<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get featured products for home page
        $featuredProducts = Product::where('prodStatus', 'available')
            ->with(['artist', 'category'])
            ->take(6)
            ->get();
            
        return view('home', compact('featuredProducts'));
    }

    /**
     * Display the products page
     */
    public function products(Request $request)
    {
        $query = Product::where('prodStatus', 'available')
            ->with(['artist', 'category']);

        // Search
        if ($request->search) {
            $query->where('prodName', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->category) {
            $query->where('categoryID', $request->category);
        }

        // Price filter
        if ($request->min_price) {
            $query->where('prodPrice', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('prodPrice', '<=', $request->max_price);
        }

        // Sort
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('prodPrice', 'asc');
                break;
            case 'price_high':
                $query->orderBy('prodPrice', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('productID', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products', compact('products', 'categories'));
    }

    /**
     * Display a single product
     */
    public function showProduct(Product $product)
    {
        $product->load(['artist', 'category']);
        return view('products.show', compact('product'));
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }
}
