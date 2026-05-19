<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        // Jika user sudah login, langsung arahkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        
        return view('auth.login');
    }

    // Memproses data login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba melakukan autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect sesuai role
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        // Jika gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    // Fungsi private untuk handle redirect dinamis
    private function redirectBasedOnRole($role)
    {
        return match ($role) {
            'super_admin' => redirect()->route('admin.dashboard'),
            'driver'      => redirect()->route('driver.dashboard'),
            'customer'    => redirect()->route('customer.home'),
            default       => redirect()->route('login'),
        };
    }
}