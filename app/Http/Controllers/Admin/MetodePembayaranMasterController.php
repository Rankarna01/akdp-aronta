<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaranMaster;

class MetodePembayaranMasterController extends Controller
{
    public function index()
    {
        return view('admin.metode-pembayaran-master.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = MetodePembayaranMaster::query();

        if (!empty($search)) {
            $query->where('nama_bank', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_rekening', 'LIKE', "%{$search}%")
                  ->orWhere('atas_nama', 'LIKE', "%{$search}%");
        }

        $metode = $query->latest()->paginate(10);
        return response()->json($metode);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        MetodePembayaranMaster::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Metode Pembayaran berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $metode = MetodePembayaranMaster::findOrFail($id);
        return response()->json($metode);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $metode = MetodePembayaranMaster::findOrFail($id);
        $metode->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Metode Pembayaran berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $metode = MetodePembayaranMaster::findOrFail($id);
        $metode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metode Pembayaran berhasil dihapus!'
        ]);
    }
}
