<?php

namespace App\Http\Middleware;

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
        // Check both the new unified session and legacy admin_id for backward compatibility
        if (!session('admin_id') && session('user_type') !== 'admin') {
            return redirect()->route('login')->with('error', 'Please login as admin to access the admin panel.');
        }

        return $next($request);
    }
}
