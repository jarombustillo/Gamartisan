<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArtistDashboardController extends Controller
{
    /**
     * Get the current artist
     */
    protected function getArtist()
    {
        return Artist::find(session('user_id'));
    }

    /**
     * Dashboard home
     */
    public function index()
    {
        $artist = $this->getArtist();
        
        $stats = [
            'total_products' => $artist->products()->count(),
            'total_sales' => $artist->orders()->sum('ordTotalPrice'),
            'total_orders' => $artist->orders()->count(),
            'pending_orders' => $artist->orders()->where('ordStatus', 'pending')->count(),
        ];

        $recentOrders = $artist->orders()->with(['buyer', 'product'])
            ->orderBy('orderDate', 'desc')
            ->take(5)
            ->get();

        $topProducts = $artist->products()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        return view('artist.dashboard', compact('artist', 'stats', 'recentOrders', 'topProducts'));
    }

    /**
     * Products list
     */
    public function products(Request $request)
    {
        $artist = $this->getArtist();
        $query = $artist->products()->with('category');

        if ($request->status) {
            $query->where('prodStatus', $request->status);
        }

        $products = $query->orderBy('productID', 'desc')->paginate(12);
        $categories = Category::all();

        return view('artist.products.index', compact('artist', 'products', 'categories'));
    }

    /**
     * Show create product form
     */
    public function createProduct()
    {
        $artist = $this->getArtist();
        $categories = Category::all();
        return view('artist.products.create', compact('artist', 'categories'));
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        $artist = $this->getArtist();

        $validated = $request->validate([
            'prodName' => 'required|string|max:255',
            'prodDesc' => 'nullable|string',
            'prodPrice' => 'required|numeric|min:0',
            'categoryID' => 'required|exists:CATEGORIES,categoryID',
            'prodImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prodStatus' => 'required|in:available,unavailable',
        ]);

        $validated['artistID'] = $artist->artistID;

        // Handle image upload
        if ($request->hasFile('prodImage')) {
            $image = $request->file('prodImage');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $filename);
            $validated['prodImage'] = '/uploads/products/' . $filename;
        } else {
            unset($validated['prodImage']);
        }

        Product::create($validated);

        return redirect()->route('artist.products')->with('success', 'Product created successfully!');
    }

    /**
     * Show edit product form
     */
    public function editProduct(Product $product)
    {
        $artist = $this->getArtist();
        
        // Ensure product belongs to this artist
        if ($product->artistID !== $artist->artistID) {
            abort(403);
        }

        $categories = Category::all();
        return view('artist.products.edit', compact('artist', 'product', 'categories'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $artist = $this->getArtist();
        
        if ($product->artistID !== $artist->artistID) {
            abort(403);
        }

        $validated = $request->validate([
            'prodName' => 'required|string|max:255',
            'prodDesc' => 'nullable|string',
            'prodPrice' => 'required|numeric|min:0',
            'categoryID' => 'required|exists:CATEGORIES,categoryID',
            'prodImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prodStatus' => 'required|in:available,unavailable',
        ]);

        // Handle image upload
        if ($request->hasFile('prodImage')) {
            // Delete old image if exists
            if ($product->prodImage && file_exists(public_path($product->prodImage))) {
                unlink(public_path($product->prodImage));
            }
            
            $image = $request->file('prodImage');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $filename);
            $validated['prodImage'] = '/uploads/products/' . $filename;
        } else {
            unset($validated['prodImage']);
        }

        $product->update($validated);

        return redirect()->route('artist.products')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroyProduct(Product $product)
    {
        $artist = $this->getArtist();
        
        if ($product->artistID !== $artist->artistID) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('artist.products')->with('success', 'Product deleted successfully!');
    }

    /**
     * Orders received
     */
    public function orders(Request $request)
    {
        $artist = $this->getArtist();
        $query = $artist->orders()->with(['buyer', 'product']);

        if ($request->status) {
            $query->where('ordStatus', $request->status);
        }

        $orders = $query->orderBy('orderDate', 'desc')->paginate(10);

        return view('artist.orders', compact('artist', 'orders'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $artist = $this->getArtist();
        
        if ($order->artistID !== $artist->artistID) {
            abort(403);
        }

        $validated = $request->validate([
            'ordStatus' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated!');
    }

    /**
     * Show profile
     */
    public function profile()
    {
        $artist = $this->getArtist();
        return view('artist.profile', compact('artist'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $artist = $this->getArtist();

        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:ARTISTS,email,' . $artist->artistID . ',artistID',
            'artBio' => 'nullable|string',
        ]);

        $artist->update($validated);
        session(['user_name' => $artist->firstName]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $artist = $this->getArtist();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $artist->artPass)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $artist->update(['artPass' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Transaction logs
     */
    public function transactions(Request $request)
    {
        $artist = $this->getArtist();
        $query = $artist->orders()->with(['buyer', 'product', 'payment', 'donation.charity']);

        if ($request->status) {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payStatus', $request->status);
            });
        }

        $orders = $query->orderBy('orderDate', 'desc')->paginate(10);

        return view('artist.transactions', compact('artist', 'orders'));
    }
}
