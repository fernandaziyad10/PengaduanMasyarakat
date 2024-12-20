<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'guest') {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
