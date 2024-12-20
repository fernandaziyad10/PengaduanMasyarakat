<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('akun.register');
    }

    // Menangani proses registrasi dan login setelah berhasil
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',  // Pastikan password dan konfirmasi password cocok
        ]);

        // Membuat user baru
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman landingPage-pengaduan setelah login
        return redirect()->route('login');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('akun.login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role == 'staff') {
                return redirect()->route('index-pengaduan'); // Redirect untuk staff
            } elseif ($user->role == 'head_staff') {
                return redirect()->route('staff-index'); // Redirect untuk head staff
            }
    
            return redirect()->route('landingPage-pengaduan'); // Default redirect untuk user biasa
        }
    
        return back()->withErrors(['email' => 'Email atau password salah.'])->with('failed', 'Gagal Login');
    }
    
    

    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();
        $request->session()->invalidate();
         $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    

    /**
     * Proses logout.
     */
    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect()->route('akun.login')
    //         ->with('success', 'Anda berhasil logout!');
    // }

    public function index()
    {
        //
        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
