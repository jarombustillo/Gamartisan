<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Donation;
use App\Models\Charity;
use App\Models\Buyer;
use App\Models\Artist;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page for a product.
     */
    public function show(Product $product)
    {
        $product->load(['artist', 'category']);
        $charities = Charity::where('charStatus', 'available')->get();
        return view('checkout', compact('product', 'charities'));
    }

    /**
     * Process the checkout and create order, payment, and donation.
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'ordQuantity' => 'required|integer|min:1',
            'payMethod' => 'required|string|in:Cash on Delivery,GCash,Bank Transfer',
            'charityID' => 'required|exists:CHARITIES,charityID',
        ]);

        $userType = session('user_type');
        $userId = session('user_id');
        $totalPrice = $product->prodPrice * $validated['ordQuantity'];

        // Determine buyerID and artistID
        if ($userType === 'buyer') {
            $buyerID = $userId;
        } else {
            // Artist buying — use their artistID as buyerID reference
            $buyerID = $userId;
        }

        // Create the order
        $order = Order::create([
            'buyerID' => $buyerID,
            'artistID' => $product->artistID,
            'productID' => $product->productID,
            'ordQuantity' => $validated['ordQuantity'],
            'ordTotalPrice' => $totalPrice,
            'orderDate' => now(),
            'ordStatus' => 'pending',
        ]);

        // Create payment record
        Payment::create([
            'orderID' => $order->orderID,
            'payAmount' => $totalPrice,
            'payMethod' => $validated['payMethod'],
            'payStatus' => 'pending',
            'datePaid' => null,
        ]);

        // Auto-allocate 10% to selected charity
        $donationAmount = round($totalPrice * 0.10, 2);
        Donation::create([
            'orderID' => $order->orderID,
            'charityID' => $validated['charityID'],
            'amountDonated' => $donationAmount,
            'dateDonated' => now(),
        ]);

        $redirectRoute = $userType === 'buyer' ? 'buyer.orders' : 'artist.orders';
        return redirect()->route($redirectRoute)->with('success', 'Order placed successfully! 10% of your purchase has been donated to charity.');
    }
}
