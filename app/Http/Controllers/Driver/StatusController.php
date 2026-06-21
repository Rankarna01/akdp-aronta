<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supir;
use App\Models\Jadwal;
use App\Models\MonitoringPerjalanan;

class StatusController extends Controller
{
    public function index()
    {
        $supir = Supir::where('user_id', Auth::id())->first();
        
        // Cari jadwal yang sedang supir jalankan sekarang
        $jadwalAktif = Jadwal::with(['rute', 'armada'])
            ->where('supir_id', $supir->id)
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('tanggal', 'asc')
            ->first();

        // Ambil riwayat update posisi terakhir (5 terakhir)
        $riwayatUpdate = [];
        if ($jadwalAktif) {
            $riwayatUpdate = MonitoringPerjalanan::where('jadwal_id', $jadwalAktif->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('driver.status.index', compact('jadwalAktif', 'riwayatUpdate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'status' => 'required|in:Persiapan,Dalam Perjalanan,Kendala,Sampai',
            'keterangan' => 'required_if:status,Kendala|nullable|string|max:500',
        ]);

        // 1. Simpan ke tabel monitoring_perjalanan
        MonitoringPerjalanan::create([
            'jadwal_id' => $request->jadwal_id,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // 2. Logika Sinkronisasi Status Jadwal Utama
        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        
        if ($request->status == 'Sampai') {
            $jadwal->update(['status' => 'Selesai']);
        } elseif ($request->status == 'Dalam Perjalanan' && $jadwal->status == 'Menunggu') {
            $jadwal->update(['status' => 'Berangkat']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status perjalanan berhasil diperbarui!'
        ]);
    }
}