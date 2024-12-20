<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsGuest
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return redirect('/'); // Redirect jika pengguna login
        }
        return $next($request);
    }
}
