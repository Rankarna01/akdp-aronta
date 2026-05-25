<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Penumpang; // Tambahkan import model Penumpang

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // ========================================================
    // FITUR REGISTRASI BARU (KHUSUS CUSTOMER)
    // ========================================================
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Tambahkan validasi untuk NIK, HP, dan Jenis Kelamin
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|max:20|unique:penumpang,nik',
            'no_hp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        // 1. Buat user baru di tabel users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // 2. Buat profil penumpang yang berelasi dengan user tersebut
        Penumpang::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nama' => $request->name, // Ambil dari input nama
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        // 3. Otomatis login setelah daftar
        Auth::login($user);

        return redirect()->route('customer.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing'); // Setelah logout, balik ke Landing Page
    }

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