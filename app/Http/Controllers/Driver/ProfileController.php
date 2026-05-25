<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Supir;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Tarik profil supir yang sedang login beserta relasi akun user-nya
        $supir = Supir::with('user')->where('user_id', Auth::id())->first();

        if (!$supir) {
            abort(403, 'Profil supir Anda belum terdaftar di sistem admin.');
        }

        return view('driver.profile.index', compact('supir'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed', // Harus sama dengan password_confirmation
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 6 karakter.'
        ]);

        $user = User::find(Auth::id());

        // Cek validitas password lama supir
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['old_password' => ['Password lama yang Anda masukkan salah.']]
            ], 422);
        }

        // Update password baru ke database
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password Anda berhasil diperbarui!'
        ]);
    }
}