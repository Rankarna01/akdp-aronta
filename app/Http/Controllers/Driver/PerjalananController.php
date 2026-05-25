<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supir;
use App\Models\Jadwal;
use Carbon\Carbon;

class PerjalananController extends Controller
{
    public function index()
    {
        $supir = Supir::where('user_id', Auth::id())->first();

        if (!$supir) {
            abort(403, 'Data profil supir tidak ditemukan.');
        }

        $hariIni = Carbon::today();

        // 1. Jadwal Hari Ini (Tanggal = Hari ini & Status belum selesai)
        $jadwalHariIni = Jadwal::with(['rute', 'armada'])
            ->where('supir_id', $supir->id)
            ->whereDate('tanggal', $hariIni)
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        // 2. Riwayat Perjalanan (Tanggal sudah lewat ATAU status sudah Selesai/Dibatalkan)
        $riwayat = Jadwal::with(['rute', 'armada'])
            ->where('supir_id', $supir->id)
            ->where(function($query) use ($hariIni) {
                $query->whereDate('tanggal', '<', $hariIni)
                      ->orWhereIn('status', ['Selesai', 'Dibatalkan']);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_berangkat', 'desc')
            ->limit(20) // Batasi 20 riwayat terakhir agar tidak berat
            ->get();

        return view('driver.perjalanan.index', compact('jadwalHariIni', 'riwayat'));
    }
}