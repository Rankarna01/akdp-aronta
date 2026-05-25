<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penumpang;
use Illuminate\Http\Request;

class PenumpangController extends Controller
{
    public function index()
    {
        return view('admin.penumpang.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = Penumpang::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }

        $penumpang = $query->latest()->paginate(10);
        return response()->json($penumpang);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:20|unique:penumpang,nik',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.'
        ]);

        Penumpang::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Penumpang berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $penumpang = Penumpang::findOrFail($id);
        return response()->json($penumpang);
    }

    public function update(Request $request, $id)
    {
        $penumpang = Penumpang::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:20|unique:penumpang,nik,' . $id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ], [
            'nik.unique' => 'NIK ini sudah digunakan oleh penumpang lain.'
        ]);

        $penumpang->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Penumpang berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $penumpang = Penumpang::findOrFail($id);
        $penumpang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Penumpang berhasil dihapus!'
        ]);
    }
}