<?php

namespace App\Http\Middleware;

use App\Models\Artist;
use App\Models\Buyer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types  The user types allowed (buyer, artist); none means any
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        if (!session('user_type') || !session('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // If specific types are required, check them
        if ($types && !in_array(session('user_type'), $types, true)) {
            return redirect()->route(session('user_type') . '.dashboard')
                ->with('error', 'You do not have access to that area.');
        }

        // Re-check the account on every request: suspending or deleting an
        // account must end sessions that are already logged in.
        $model = ['buyer' => Buyer::class, 'artist' => Artist::class][session('user_type')] ?? null;
        if ($model) {
            $account = $model::find(session('user_id'));
            if (!$account || $account->status === 'suspended') {
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => $account ? 'Your account has been suspended. Please contact support.' : 'Please login to continue.',
                ]);
            }
        }

        return $next($request);
    }
}
