<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;  // Keep this import
use Illuminate\Http\RedirectResponse;  // Add this import
use Illuminate\Support\Facades\Auth;

class HeadStaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'head_staff') {
            return $next($request);
        }

        // Return a RedirectResponse in case of unauthorized access
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}

