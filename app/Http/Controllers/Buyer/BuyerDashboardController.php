<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BuyerDashboardController extends Controller
{
    /**
     * Get the current buyer
     */
    protected function getBuyer()
    {
        return Buyer::find(session('user_id'));
    }

    /**
     * Dashboard home
     */
    public function index()
    {
        $buyer = $this->getBuyer();
        $recentOrders = $buyer->orders()->with(['product', 'artist'])
            ->orderBy('orderDate', 'desc')
            ->take(5)
            ->get();
        
        $stats = [
            'total_orders' => $buyer->orders()->count(),
            'total_spent' => $buyer->orders()->sum('ordTotalPrice'),
            'pending_orders' => $buyer->orders()->where('ordStatus', 'pending')->count(),
        ];

        return view('buyer.dashboard', compact('buyer', 'recentOrders', 'stats'));
    }

    /**
     * Order history
     */
    public function orders(Request $request)
    {
        $buyer = $this->getBuyer();
        $query = $buyer->orders()->with(['product', 'artist']);

        if ($request->status) {
            $query->where('ordStatus', $request->status);
        }

        $orders = $query->orderBy('orderDate', 'desc')->paginate(10);

        return view('buyer.orders', compact('buyer', 'orders'));
    }

    /**
     * Show profile
     */
    public function profile()
    {
        $buyer = $this->getBuyer();
        return view('buyer.profile', compact('buyer'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $buyer = $this->getBuyer();

        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:BUYERS,email,' . $buyer->buyerID . ',buyerID',
        ]);

        $buyer->update($validated);

        // Update session name
        session(['user_name' => $buyer->firstName]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $buyer = $this->getBuyer();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $buyer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $buyer->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Transaction logs
     */
    public function transactions(Request $request)
    {
        $buyer = $this->getBuyer();
        $query = $buyer->orders()->with(['product', 'artist', 'payment', 'donation.charity']);

        if ($request->status) {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('payStatus', $request->status);
            });
        }

        $orders = $query->orderBy('orderDate', 'desc')->paginate(10);

        return view('buyer.transactions', compact('buyer', 'orders'));
    }
}
