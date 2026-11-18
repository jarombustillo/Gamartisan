<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\PurchasesAsCurrentUser;
use App\Models\CartItem;
use App\Models\Charity;
use App\Models\Product;
use App\Services\OrderException;
use App\Services\OrderService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use PurchasesAsCurrentUser;

    /**
     * Get current user's cart query.
     */
    private function cartQuery()
    {
        return CartItem::where('userID', session('user_id'))
            ->where('userType', session('user_type'));
    }

    private function respond(Request $request, bool $ok, string $message, array $extra = [], int $status = 422)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $ok, 'message' => $message] + $extra, $ok ? 200 : $status);
        }

        return back()->with($ok ? 'success' : 'error', $message);
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
        if ($this->isOwnProduct($product)) {
            return $this->respond($request, false, 'You cannot buy your own product.');
        }

        if ($product->prodStatus !== 'available' || $product->prodStock <= 0) {
            return $this->respond($request, false, 'This product is out of stock.');
        }

        $existing = $this->cartQuery()->where('productID', $product->productID)->first();

        if (!$existing) {
            try {
                CartItem::create([
                    'userID' => session('user_id'),
                    'userType' => session('user_type'),
                    'productID' => $product->productID,
                    'quantity' => 1,
                    'dateAdded' => now(),
                ]);
            } catch (UniqueConstraintViolationException) {
                // A parallel request (double click) added it first; treat as added.
            }
        } elseif ($existing->quantity >= $product->prodStock) {
            return $this->respond($request, false, 'Cannot add more. Only ' . $product->prodStock . ' in stock.');
        } else {
            // Conditional increment so parallel clicks cannot push quantity past stock.
            CartItem::where('cartItemID', $existing->cartItemID)
                ->where('quantity', '<', $product->prodStock)
                ->increment('quantity');
        }

        return $this->respond($request, true, "{$product->prodName} added to cart!", [
            'cartCount' => $this->cartQuery()->count(),
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->userID != session('user_id') || $cartItem->userType != session('user_type')) {
            abort(403);
        }

        $data = $request->json()->all() ?: $request->all();
        $stock = (int) optional($cartItem->product)->prodStock;
        $quantity = max(1, min((int) ($data['quantity'] ?? 1), max($stock, 1)));

        $cartItem->update(['quantity' => $quantity]);

        return $this->respond($request, true, 'Cart updated.', ['quantity' => $quantity, 'stock' => $stock]);
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
    public function checkout(Request $request, OrderService $orders)
    {
        $validated = $request->validate([
            'payMethod' => 'required|string|in:Cash on Delivery',
            'charityID' => 'required|integer',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'integer',
        ]);

        $selectedIds = array_values(array_unique(array_map('intval', $validated['selected_items'])));
        $cartItems = $this->cartQuery()->whereIn('cartItemID', $selectedIds)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Please select at least one item.');
        }

        if ($cartItems->contains(fn ($item) => $item->product && $this->isOwnProduct($item->product))) {
            return back()->with('error', 'You cannot buy your own product. Please remove it from your cart.');
        }

        $quantities = [];
        foreach ($cartItems as $item) {
            $quantities[$item->productID] = ($quantities[$item->productID] ?? 0) + $item->quantity;
        }
        $itemIds = $cartItems->pluck('cartItemID')->all();

        try {
            $orders->place(
                $this->purchaserBuyerID(),
                (string) session('user_name'),
                $quantities,
                (int) $validated['charityID'],
                $validated['payMethod'],
                // Runs inside the order transaction: if a double-submitted request already
                // checked these items out, they are gone and this whole purchase rolls back.
                function () use ($itemIds) {
                    $deleted = $this->cartQuery()->whereIn('cartItemID', $itemIds)->delete();
                    if ($deleted !== count($itemIds)) {
                        throw new OrderException('These items were already ordered.');
                    }
                },
            );
        } catch (OrderException $e) {
            return back()->with('error', $e->getMessage() . ' Please review your cart.');
        }

        return redirect()->route($this->purchasesRoute())->with('success', 'All items ordered successfully! 10% of your purchase has been donated to charity.');
    }
}
