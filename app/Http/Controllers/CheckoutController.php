<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\PurchasesAsCurrentUser;
use App\Models\Charity;
use App\Models\Product;
use App\Services\OrderException;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    use PurchasesAsCurrentUser;

    /**
     * Show the checkout page for a product.
     */
    public function show(Product $product)
    {
        if ($this->isOwnProduct($product)) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You cannot buy your own product.');
        }

        if ($product->prodStatus !== 'available' || $product->prodStock <= 0) {
            return redirect()->route('products.show', $product)
                ->with('error', 'This product is currently out of stock.');
        }

        $product->load(['artist', 'category']);
        $charities = Charity::where('charStatus', 'available')->get();
        // One-time token: a double-clicked or re-sent form cannot place the order twice.
        $checkoutToken = Str::random(40);

        return view('checkout', compact('product', 'charities', 'checkoutToken'));
    }

    /**
     * Process the checkout and create order, payment, and donation.
     */
    public function store(Request $request, Product $product, OrderService $orders)
    {
        $validated = $request->validate([
            'ordQuantity' => 'required|integer|min:1|max:1000',
            'payMethod' => 'required|string|in:Cash on Delivery',
            'charityID' => 'required|integer',
            'checkout_token' => 'required|string|size:40',
        ]);

        if ($this->isOwnProduct($product)) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You cannot buy your own product.');
        }

        // Cache::add is atomic: only the first request with this token gets through.
        if (!Cache::add('checkout-token:' . $validated['checkout_token'], true, now()->addHour())) {
            return redirect()->route($this->purchasesRoute())->with('success', 'Your order was already placed.');
        }

        try {
            $orders->place(
                $this->purchaserBuyerID(),
                (string) session('user_name'),
                [$product->productID => (int) $validated['ordQuantity']],
                (int) $validated['charityID'],
                $validated['payMethod'],
            );
        } catch (OrderException $e) {
            Cache::forget('checkout-token:' . $validated['checkout_token']); // let them retry
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route($this->purchasesRoute())->with('success', 'Order placed successfully! 10% of your purchase has been donated to charity, and 5% supports the Gamartisan platform.');
    }
}
