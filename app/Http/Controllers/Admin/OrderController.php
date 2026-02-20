<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'artist', 'product', 'payment']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('ordStatus', $request->status);
        }

        // Search by order ID or buyer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('orderID', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function($bq) use ($search) {
                      $bq->where('firstName', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->has('from_date') && $request->from_date) {
            $query->where('orderDate', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('orderDate', '<=', $request->to_date . ' 23:59:59');
        }

        $orders = $query->orderBy('orderDate', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['buyer', 'artist', 'product', 'payment', 'donation.charity']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'ordStatus' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
