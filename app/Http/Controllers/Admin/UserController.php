<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Buyer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a combined listing of buyers and artists.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $roleFilter = $request->role;

        // Query Buyers
        $buyersQuery = Buyer::query();
        if ($search) {
            $buyersQuery->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Query Artists
        $artistsQuery = Artist::withCount('products');
        if ($search) {
            $artistsQuery->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Build unified collection
        $buyers = $buyersQuery->get()->map(function ($buyer) {
            return (object) [
                'id' => $buyer->buyerID,
                'name' => $buyer->fullName,
                'firstName' => $buyer->firstName,
                'email' => $buyer->email,
                'role' => 'Buyer',
                'status' => $buyer->status,
                'extra' => $buyer->orders()->count() . ' orders',
                'viewUrl' => route('admin.buyers.show', $buyer),
                'editUrl' => route('admin.buyers.edit', $buyer),
                'toggleUrl' => route('admin.buyers.toggle-status', $buyer),
                'deleteUrl' => route('admin.buyers.destroy', $buyer),
                'deleteLabel' => 'buyer',
            ];
        });

        $artists = $artistsQuery->get()->map(function ($artist) {
            return (object) [
                'id' => $artist->artistID,
                'name' => $artist->fullName,
                'firstName' => $artist->firstName,
                'email' => $artist->email,
                'role' => 'Artist',
                'status' => $artist->status,
                'extra' => $artist->products_count . ' products',
                'viewUrl' => route('admin.artists.show', $artist),
                'editUrl' => route('admin.artists.edit', $artist),
                'toggleUrl' => route('admin.artists.toggle-status', $artist),
                'deleteUrl' => route('admin.artists.destroy', $artist),
                'deleteLabel' => 'artist',
            ];
        });

        // Filter by role
        if ($roleFilter === 'buyer') {
            $users = $buyers->sortByDesc('id')->values();
        } elseif ($roleFilter === 'artist') {
            $users = $artists->sortByDesc('id')->values();
        } else {
            $users = $buyers->concat($artists)->sortByDesc('name')->values();
        }

        return view('admin.users.index', compact('users'));
    }
}
