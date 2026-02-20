<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Artist;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the join/role selection page
     */
    public function join()
    {
        return view('auth.join');
    }

    /**
     * Show the buyer registration form
     */
    public function registerBuyer()
    {
        return view('auth.register-buyer');
    }

    /**
     * Show the seller registration form
     */
    public function registerSeller()
    {
        return view('auth.register-seller');
    }

    /**
     * Handle buyer registration
     */
    public function storeBuyer(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:BUYERS,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $buyer = Buyer::create([
            'firstName' => $validated['firstName'],
            'midName' => $validated['midName'] ?? null,
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login the user
        session([
            'user_type' => 'buyer',
            'user_id' => $buyer->buyerID,
            'user_name' => $buyer->firstName,
        ]);

        return redirect()->route('products')->with('success', 'Welcome to Gamartisan!');
    }

    /**
     * Handle seller/artist registration
     */
    public function storeSeller(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'midName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:ARTISTS,email',
            'password' => 'required|string|min:6|confirmed',
            'artBio' => 'nullable|string',
        ]);

        $artist = Artist::create([
            'firstName' => $validated['firstName'],
            'midName' => $validated['midName'] ?? null,
            'lastName' => $validated['lastName'],
            'email' => $validated['email'],
            'artPass' => Hash::make($validated['password']),
            'artBio' => $validated['artBio'] ?? null,
        ]);

        // Login the user
        session([
            'user_type' => 'artist',
            'user_id' => $artist->artistID,
            'user_name' => $artist->firstName,
        ]);

        return redirect()->route('artist.dashboard')->with('success', 'Welcome to Gamartisan!');
    }

    /**
     * Show the login form
     */
    public function showLogin()
    {
        // If already logged in, redirect appropriately
        if (session('user_type') === 'buyer') {
            return redirect()->route('products');
        }
        if (session('user_type') === 'artist') {
            return redirect()->route('artist.dashboard');
        }
        if (session('user_type') === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login - checks buyers, artists, and admin tables
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to find buyer first (by email)
        $buyer = Buyer::where('email', $request->email)->first();
        if ($buyer && Hash::check($request->password, $buyer->password)) {
            // Check if account is suspended
            if ($buyer->status === 'suspended') {
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->withInput($request->only('email'));
            }
            session([
                'user_type' => 'buyer',
                'user_id' => $buyer->buyerID,
                'user_name' => $buyer->firstName,
            ]);
            return redirect()->route('products')->with('success', 'Welcome back!');
        }

        // Try to find artist (by email)
        $artist = Artist::where('email', $request->email)->first();
        if ($artist && Hash::check($request->password, $artist->artPass)) {
            // Check if account is suspended
            if ($artist->status === 'suspended') {
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->withInput($request->only('email'));
            }
            session([
                'user_type' => 'artist',
                'user_id' => $artist->artistID,
                'user_name' => $artist->firstName,
            ]);
            return redirect()->route('artist.dashboard')->with('success', 'Welcome back!');
        }

        // Try to find admin (by username)
        $admin = Admin::where('adminUser', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->adminPass)) {
            session([
                'user_type' => 'admin',
                'user_id' => $admin->adminID,
                'user_name' => $admin->adminUser,
                'admin_id' => $admin->adminID, // Keep for backward compatibility with admin middleware
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome to the Admin Dashboard!');
        }

        return back()->withErrors([
            'email' => 'Invalid email/username or password.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->forget(['user_type', 'user_id', 'user_name', 'admin_id']);
        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }
}
