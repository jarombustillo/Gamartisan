<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type  The user type required (buyer or artist)
     */
    public function handle(Request $request, Closure $next, string $type = null): Response
    {
        if (!session('user_type') || !session('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // If specific type required, check it
        if ($type && session('user_type') !== $type) {
            return redirect()->route(session('user_type') . '.dashboard')
                ->with('error', 'You do not have access to that area.');
        }

        return $next($request);
    }
}
