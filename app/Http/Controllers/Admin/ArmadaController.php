<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    public function index()
    {
        return view('admin.armada.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = Armada::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_bus', 'LIKE', "%{$search}%")
                  ->orWhere('plat_nomor', 'LIKE', "%{$search}%")
                  ->orWhere('tipe_bus', 'LIKE', "%{$search}%");
            });
        }

        $armadas = $query->latest()->paginate(10);
        return response()->json($armadas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bus' => 'required|string|max:255',
            // Ubah unique:armadas menjadi unique:armada
            'plat_nomor' => 'required|string|max:20|unique:armada,plat_nomor',
            'tipe_bus' => 'required|in:Ekonomi,Bisnis,Executive,Royal Class',
            'total_kursi' => 'required|integer|min:1|max:60',
            'status' => 'required|in:Aktif,Maintenance,Non-Aktif',
        ]);

        Armada::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Armada berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $armada = Armada::findOrFail($id);
        return response()->json($armada);
    }

    public function update(Request $request, $id)
    {
        $armada = Armada::findOrFail($id);

        $request->validate([
            'nama_bus' => 'required|string|max:255',
            // Ubah unique:armadas menjadi unique:armada
            'plat_nomor' => 'required|string|max:20|unique:armada,plat_nomor,' . $id,
            'tipe_bus' => 'required|in:Ekonomi,Bisnis,Executive,Royal Class',
            'total_kursi' => 'required|integer|min:1|max:60',
            'status' => 'required|in:Aktif,Maintenance,Non-Aktif',
        ]);

        $armada->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Armada berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $armada = Armada::findOrFail($id);
        $armada->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Armada berhasil dihapus!'
        ]);
    }
}