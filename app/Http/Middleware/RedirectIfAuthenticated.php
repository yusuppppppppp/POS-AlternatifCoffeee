<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // If user is admin, redirect to dashboard
            if (auth()->user()->usertype === 'admin') {
                return redirect()->route('dashboard');
            }
            // If user is regular user, redirect to kasir
            return redirect()->route('kasir');
        }

        return $next($request);
    }
}
