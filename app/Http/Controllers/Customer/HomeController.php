<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Tiket;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil tanggal hari ini berdasarkan zona waktu WIB (Asia/Jakarta)
        $hariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // 2. Tarik jadwal aktif
        $jadwalAktif = Jadwal::with(['rute', 'armada'])
            ->where('supir_id', Auth::id()) // Pastikan milik supir yg login
            ->where('tanggal', '>=', $hariIni) // KUNCI: Abaikan jadwal hari kemarin yang nyangkut
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderByRaw("FIELD(status, 'Berangkat', 'Menunggu')") // Prioritaskan yg sedang 'Berangkat'
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_berangkat', 'asc')
            ->first();

        $totalPenumpang = 0;
        $kursiKosong = 0;

        if ($jadwalAktif) {
            // Hitung total penumpang (Tiket aktif)
            $totalPenumpang = Tiket::where('jadwal_id', $jadwalAktif->id)
                                   ->where('status_tiket', '!=', 'Dibatalkan')
                                   ->count();

            // Hitung sisa kursi kosong
            $totalKursi = $jadwalAktif->armada->total_kursi ?? 0;
            $kursiKosong = $totalKursi - $totalPenumpang;
            
            if ($kursiKosong < 0) $kursiKosong = 0; // Mencegah nilai minus
        }

        return view('driver.dashboard.index', compact('jadwalAktif', 'totalPenumpang', 'kursiKosong'));
    }
}