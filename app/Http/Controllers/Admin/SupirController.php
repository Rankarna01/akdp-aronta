<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SupirController extends Controller
{
    public function index()
    {
        return view('admin.supir.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        // Eager load relasi 'user' agar query lebih efisien
        $query = Supir::with('user');

        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('no_ktp', 'LIKE', "%{$search}%")
              ->orWhere('no_sim', 'LIKE', "%{$search}%");
        }

        $supir = $query->latest()->paginate(10);
        return response()->json($supir);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_ktp' => 'required|string|max:20|unique:supir,no_ktp',
            'no_sim' => 'required|string|max:20|unique:supir,no_sim',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Cuti,Non-Aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat Akun Login (User)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'driver', // Set role sebagai supir
            ]);

            // 2. Upload Foto Jika Ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('profile_supir', 'public');
            }

            // 3. Simpan Profil Supir
            Supir::create([
                'user_id' => $user->id,
                'no_ktp' => $request->no_ktp,
                'no_sim' => $request->no_sim,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'foto' => $fotoPath,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data dan Akun Supir berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    public function edit($id)
    {
        $supir = Supir::with('user')->findOrFail($id);
        return response()->json($supir);
    }

    public function update(Request $request, $id)
    {
        $supir = Supir::findOrFail($id);
        $user = User::findOrFail($supir->user_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6', // Password boleh kosong jika tidak diubah
            'no_ktp' => 'required|string|max:20|unique:supir,no_ktp,' . $supir->id,
            'no_sim' => 'required|string|max:20|unique:supir,no_sim,' . $supir->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Aktif,Cuti,Non-Aktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Update User
            $userData = ['name' => $request->name, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Update Foto
            $fotoPath = $supir->foto;
            if ($request->hasFile('foto')) {
                if ($fotoPath) Storage::disk('public')->delete($fotoPath); // Hapus foto lama
                $fotoPath = $request->file('foto')->store('profile_supir', 'public');
            }

            // Update Supir
            $supir->update([
                'no_ktp' => $request->no_ktp,
                'no_sim' => $request->no_sim,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'status' => $request->status,
                'foto' => $fotoPath,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data Supir berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    public function destroy($id)
    {
        $supir = Supir::findOrFail($id);
        if ($supir->foto) Storage::disk('public')->delete($supir->foto);
        
        // Hapus akun user otomatis menghapus supir (karena onDelete Cascade)
        User::destroy($supir->user_id); 

        return response()->json(['success' => true, 'message' => 'Data dan Akun Supir berhasil dihapus!']);
    }
}