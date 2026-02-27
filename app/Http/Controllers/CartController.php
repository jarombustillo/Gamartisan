<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Donation;
use App\Models\Charity;
use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get current user's cart query.
     */
    private function cartQuery()
    {
        return CartItem::where('userID', session('user_id'))
            ->where('userType', session('user_type'));
    }

    /**
     * Display the cart page.
     */
    public function index()
    {
        $cartItems = $this->cartQuery()
            ->with('product.artist')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->prodPrice * $item->quantity;
        });

        $charities = Charity::where('charStatus', 'available')->get();

        return view('cart', compact('cartItems', 'total', 'charities'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $userID = session('user_id');
        $userType = session('user_type');

        // Check if already in cart
        $existing = CartItem::where('userID', $userID)
            ->where('userType', $userType)
            ->where('productID', $product->productID)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            CartItem::create([
                'userID' => $userID,
                'userType' => $userType,
                'productID' => $product->productID,
                'quantity' => 1,
                'dateAdded' => now(),
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $cartCount = CartItem::where('userID', $userID)->where('userType', $userType)->count();
            return response()->json([
                'success' => true,
                'message' => "{$product->prodName} added to cart!",
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', "{$product->prodName} added to cart!");
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $data = $request->json()->all() ?: $request->all();
        $quantity = $data['quantity'] ?? 1;

        if ($cartItem->userID != session('user_id') || $cartItem->userType != session('user_type')) {
            abort(403);
        }

        $cartItem->update(['quantity' => max(1, (int) $quantity)]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->userID != session('user_id') || $cartItem->userType != session('user_type')) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Checkout selected cart items — creates an order for each selected item.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'payMethod' => 'required|string|in:Cash on Delivery,GCash,Bank Transfer',
            'charityID' => 'required|exists:CHARITIES,charityID',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'integer',
        ]);

        $selectedIds = $validated['selected_items'];

        $cartItems = $this->cartQuery()
            ->whereIn('cartItemID', $selectedIds)
            ->with('product.artist')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Please select at least one item.');
        }

        $userType = session('user_type');
        $userID = session('user_id');
        $charity = Charity::find($validated['charityID']);
        $charityName = $charity ? $charity->charName : 'charity';

        foreach ($cartItems as $item) {
            $totalPrice = $item->product->prodPrice * $item->quantity;
            $sellerAmount = round($totalPrice * 0.85, 2);
            $platformFee = round($totalPrice * 0.05, 2);
            $donationAmount = round($totalPrice * 0.10, 2);

            $order = Order::create([
                'buyerID' => $userID,
                'artistID' => $item->product->artistID,
                'productID' => $item->product->productID,
                'ordQuantity' => $item->quantity,
                'ordTotalPrice' => $totalPrice,
                'sellerAmount' => $sellerAmount,
                'platformFee' => $platformFee,
                'orderDate' => now(),
                'ordStatus' => 'pending',
            ]);

            Payment::create([
                'orderID' => $order->orderID,
                'payAmount' => $totalPrice,
                'payMethod' => $validated['payMethod'],
                'payStatus' => 'pending',
                'datePaid' => null,
            ]);

            Donation::create([
                'orderID' => $order->orderID,
                'charityID' => $validated['charityID'],
                'amountDonated' => $donationAmount,
                'dateDonated' => now(),
            ]);

            // Notify artist
            Notification::send('order', "New order #{$order->orderID} for {$item->product->prodName} (Qty: {$item->quantity}) — ₱" . number_format($sellerAmount, 2) . " earnings", [
                'artistID' => $item->product->artistID,
            ]);

            // Notify admins
            $adminIds = Admin::pluck('adminID')->toArray();
            if (!empty($adminIds)) {
                Notification::send('order', "New order #{$order->orderID} — ₱" . number_format($totalPrice, 2) . " from " . session('user_name'), [
                    'adminID' => $adminIds,
                ]);
            }
        }

        // Notify buyer about donations
        $totalDonation = $cartItems->sum(function ($item) {
            return round($item->product->prodPrice * $item->quantity * 0.10, 2);
        });

        if ($userType === 'buyer') {
            Notification::send('donation', "₱" . number_format($totalDonation, 2) . " from your orders was donated to {$charityName}", [
                'buyerID' => $userID,
            ]);
        }

        // Clear the cart
        // Clear only the ordered items from the cart
        $this->cartQuery()->whereIn('cartItemID', $selectedIds)->delete();

        $redirectRoute = $userType === 'buyer' ? 'buyer.orders' : 'artist.orders';
        return redirect()->route($redirectRoute)->with('success', 'All items ordered successfully! 10% of your purchase has been donated to charity.');
    }
}
