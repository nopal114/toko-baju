<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login'); // Belum login
        }

        if (Auth::user()->role !== 'customer') {
            return redirect('/login'); // Bukan customer
        }

        return $next($request);
    }
}

