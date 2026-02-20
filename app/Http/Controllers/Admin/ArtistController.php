<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArtistController extends Controller
{
    /**
     * Display a listing of artists.
     */
    public function index(Request $request)
    {
        $query = Artist::withCount('products');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $artists = $query->orderBy('artistID', 'desc')->paginate(10);

        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Show the form for creating a new artist.
     */
    public function create()
    {
        return view('admin.artists.create');
    }

    /**
     * Store a newly created artist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:ARTISTS,email',
            'artPass' => 'required|string|min:6|confirmed',
            'artBio' => 'nullable|string',
        ]);

        $validated['artPass'] = Hash::make($validated['artPass']);

        Artist::create($validated);

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist created successfully.');
    }

    /**
     * Display the specified artist.
     */
    public function show(Artist $artist)
    {
        $artist->load(['products.category', 'orders.buyer']);
        return view('admin.artists.show', compact('artist'));
    }

    /**
     * Show the form for editing the specified artist.
     */
    public function edit(Artist $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    /**
     * Update the specified artist.
     */
    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:ARTISTS,email,' . $artist->artistID . ',artistID',
            'artPass' => 'nullable|string|min:6|confirmed',
            'artBio' => 'nullable|string',
        ]);

        if ($request->filled('artPass')) {
            $validated['artPass'] = Hash::make($validated['artPass']);
        } else {
            unset($validated['artPass']);
        }

        $artist->update($validated);

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist updated successfully.');
    }

    /**
     * Remove the specified artist.
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist deleted successfully.');
    }

    /**
     * Toggle the status of the artist (suspend/reactivate).
     */
    public function toggleStatus(Artist $artist)
    {
        $newStatus = $artist->status === 'suspended' ? 'active' : 'suspended';
        $artist->update(['status' => $newStatus]);

        $message = $newStatus === 'suspended' 
            ? 'Artist account suspended successfully.' 
            : 'Artist account reactivated successfully.';

        return redirect()->route('admin.artists.index')->with('success', $message);
    }
}
