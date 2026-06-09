<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    public function index()
    {
        return view('admin.rute.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = Rute::query();

        if (!empty($search)) {
            $query->where('kota_asal', 'LIKE', "%{$search}%")
                  ->orWhere('kota_tujuan', 'LIKE', "%{$search}%");
        }

        $rute = $query->latest()->paginate(10);
        return response()->json($rute);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        Rute::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Rute berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $rute = Rute::findOrFail($id);
        return response()->json($rute);
    }

    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);

        $request->validate([
            'kota_asal' => 'required|string|max:255',
            'kota_tujuan' => 'required|string|max:255',
            'harga_dasar' => 'required|numeric|min:0',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $rute->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data Rute berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Rute berhasil dihapus!'
        ]);
    }
}