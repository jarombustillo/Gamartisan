<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin()
    {
        if (session('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('adminUser', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->adminPass)) {
            session(['admin_id' => $admin->adminID]);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome to the Admin Dashboard!');
        }

        return back()->withErrors([
            'credentials' => 'Invalid username or password.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle admin logout.
     */
    public function logout()
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
