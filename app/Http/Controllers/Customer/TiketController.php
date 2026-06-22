<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rute;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Tiket;

class TiketController extends Controller
{
    // Menampilkan Form Pencarian (Rute Langsung)
    public function index()
    {
        // Langsung tarik data Rute yang aktif
        $rute = Rute::where('status', 'Aktif')->get();
        return view('customer.tiket.index', compact('rute'));
    }

    // Menampilkan Hasil Pencarian
    public function search(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'tanggal' => 'required|date',
            'catatan_titik' => 'nullable|string|max:255'
        ]);

        $query = Jadwal::with(['rute', 'armada'])
            ->withCount(['tiket' => function ($q) {
                // Hitung tiket yang sudah dipesan/dibayar
                $q->where('status_tiket', '!=', 'Dibatalkan');
            }])
            ->where('rute_id', $request->rute_id)
            ->whereDate('tanggal', $request->tanggal)
            ->whereIn('status', ['Menunggu', 'Berangkat']);

        // Jika mencari tiket untuk hari ini, sembunyikan jam yang sudah lewat
        if ($request->tanggal == now()->toDateString()) {
            $query->whereTime('waktu_berangkat', '>', now()->toTimeString());
        }

        $jadwal = $query->orderBy('waktu_berangkat', 'asc')->get();
        $params = $request->all();
        
        // Ambil info rute yang dipilih untuk header
        $ruteTerpilih = Rute::find($request->rute_id);

        return view('customer.tiket.hasil', compact('jadwal', 'params', 'ruteTerpilih'));
    }

    public function pilihKursi($jadwal_id)
    {
        $jadwal = Jadwal::with(['rute', 'armada'])->findOrFail($jadwal_id);

        $semuaKursi = Kursi::where('armada_id', $jadwal->armada_id)
                           ->where('status', 'Aktif')
                           ->orderByRaw('CAST(nomor_kursi AS UNSIGNED) ASC')
                           ->get();

        $kursiTerisi = Tiket::where('jadwal_id', $jadwal_id)
                            ->where('status_tiket', '!=', 'Dibatalkan')
                            ->pluck('kursi_id')
                            ->toArray();

        return view('customer.tiket.pilih-kursi', compact('jadwal', 'semuaKursi', 'kursiTerisi'));
    }
}