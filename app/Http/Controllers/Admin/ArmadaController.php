<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                  ->orWhere('nomor_pintu', 'LIKE', "%{$search}%")
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
            'plat_nomor' => 'required|string|max:20|unique:armada,plat_nomor',
            'nomor_pintu' => 'nullable|string|max:10',
            'tipe_bus' => 'required|in:Ekonomi,Bisnis,Executive,Royal Class',
            'total_kursi' => 'required|integer|min:1|max:60',
            'status' => 'required|in:Aktif,Maintenance,Non-Aktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('armada', 'public');
        }

        Armada::create($data);

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
            'plat_nomor' => 'required|string|max:20|unique:armada,plat_nomor,' . $id,
            'nomor_pintu' => 'nullable|string|max:10',
            'tipe_bus' => 'required|in:Ekonomi,Bisnis,Executive,Royal Class',
            'total_kursi' => 'required|integer|min:1|max:60',
            'status' => 'required|in:Aktif,Maintenance,Non-Aktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['gambar']);

        if ($request->hasFile('gambar')) {
            if ($armada->gambar && Storage::disk('public')->exists($armada->gambar)) {
                Storage::disk('public')->delete($armada->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('armada', 'public');
        }

        $armada->update($data);

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