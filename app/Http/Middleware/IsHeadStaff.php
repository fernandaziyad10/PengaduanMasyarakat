<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsHeadStaff
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'head_staff') {
            return $next($request);
        }
        return redirect('/'); // Redirect jika bukan head_staff
    }
}
