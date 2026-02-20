<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BuyerController extends Controller
{
    /**
     * Display a listing of buyers.
     */
    public function index(Request $request)
    {
        $query = Buyer::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $buyers = $query->orderBy('buyerID', 'desc')->paginate(10);

        return view('admin.buyers.index', compact('buyers'));
    }

    /**
     * Show the form for creating a new buyer.
     */
    public function create()
    {
        return view('admin.buyers.create');
    }

    /**
     * Store a newly created buyer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:BUYERS,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Buyer::create($validated);

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer created successfully.');
    }

    /**
     * Display the specified buyer.
     */
    public function show(Buyer $buyer)
    {
        $buyer->load(['orders.product', 'reviews.product']);
        return view('admin.buyers.show', compact('buyer'));
    }

    /**
     * Show the form for editing the specified buyer.
     */
    public function edit(Buyer $buyer)
    {
        return view('admin.buyers.edit', compact('buyer'));
    }

    /**
     * Update the specified buyer.
     */
    public function update(Request $request, Buyer $buyer)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:BUYERS,email,' . $buyer->buyerID . ',buyerID',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $buyer->update($validated);

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer updated successfully.');
    }

    /**
     * Remove the specified buyer.
     */
    public function destroy(Buyer $buyer)
    {
        $buyer->delete();

        return redirect()->route('admin.buyers.index')
            ->with('success', 'Buyer deleted successfully.');
    }

    /**
     * Toggle the status of the buyer (suspend/reactivate).
     */
    public function toggleStatus(Buyer $buyer)
    {
        $newStatus = $buyer->status === 'suspended' ? 'active' : 'suspended';
        $buyer->update(['status' => $newStatus]);

        $message = $newStatus === 'suspended' 
            ? 'Buyer account suspended successfully.' 
            : 'Buyer account reactivated successfully.';

        return redirect()->route('admin.buyers.index')->with('success', $message);
    }
}
