<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Armada;
use App\Models\Supir;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $rute = Rute::where('status', 'Aktif')->get();
        $armada = Armada::where('status', 'Aktif')->get();
        // Supir harus memanggil relasi 'user' untuk mengambil namanya
        $supir = Supir::with('user')->where('status', 'Aktif')->get();
        
        return view('admin.jadwal.index', compact('rute', 'armada', 'supir'));
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        // Eager load semua relasi
        $query = Jadwal::with(['rute', 'armada', 'supir.user']);

        if (!empty($search)) {
            $query->whereHas('rute', function($q) use ($search) {
                $q->where('kota_asal', 'LIKE', "%{$search}%")
                  ->orWhere('kota_tujuan', 'LIKE', "%{$search}%");
            })->orWhereHas('armada', function($q) use ($search) {
                $q->where('nama_bus', 'LIKE', "%{$search}%");
            })->orWhere('tanggal', 'LIKE', "%{$search}%");
        }

        // Urutkan jadwal dari tanggal & waktu terbaru
        $jadwal = $query->orderBy('tanggal', 'desc')->orderBy('waktu_berangkat', 'desc')->paginate(10);
        return response()->json($jadwal);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'armada_id' => 'required|exists:armada,id',
            'supir_id' => 'required|exists:supir,id',
            'tanggal' => 'required|date',
            'waktu_berangkat' => 'required',
            'waktu_tiba' => 'nullable',
            'harga_tiket' => 'required|numeric|min:0',
            'status' => 'required|in:Menunggu,Berangkat,Selesai,Dibatalkan',
        ]);

        Jadwal::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal Keberangkatan berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'armada_id' => 'required|exists:armada,id',
            'supir_id' => 'required|exists:supir,id',
            'tanggal' => 'required|date',
            'waktu_berangkat' => 'required',
            'waktu_tiba' => 'nullable',
            'harga_tiket' => 'required|numeric|min:0',
            'status' => 'required|in:Menunggu,Berangkat,Selesai,Dibatalkan',
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal Keberangkatan berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal Keberangkatan berhasil dihapus!'
        ]);
    }
}