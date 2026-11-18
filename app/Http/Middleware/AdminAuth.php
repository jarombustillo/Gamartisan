<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Both keys must agree: a buyer/artist session must never pass as admin.
        $adminId = session('admin_id');
        if (session('user_type') !== 'admin' || !$adminId || !Admin::whereKey($adminId)->exists()) {
            return redirect()->route('login')->with('error', 'Please login as admin to access the admin panel.');
        }

        return $next($request);
    }
}
