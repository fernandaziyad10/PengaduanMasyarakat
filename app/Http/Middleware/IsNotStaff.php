<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsNotStaff
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login dan memiliki akses sebagai staff
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            session()->flash('failed', 'Anda tidak bisa mengakses halaman ini!!');
            return redirect()->route('landingPage-pengaduan');
        }

        return $next($request); // Melanjutkan jika pengguna adalah staff
    }
}
