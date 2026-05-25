<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supir;
use App\Models\Jadwal;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cari data Supir berdasarkan User ID yang sedang login
        $supir = Supir::where('user_id', Auth::id())->first();

        $jadwalAktif = null;
        $totalPenumpang = 0;
        $kursiKosong = 0;

        if ($supir) {
            // 2. Cari Jadwal Aktif (Menunggu atau Berangkat) untuk Supir ini
            $jadwalAktif = Jadwal::with(['rute', 'armada'])
                                 ->withCount(['tiket' => function ($query) {
                                     $query->where('status_pembayaran', 'Paid')
                                           ->where('status_tiket', 'Aktif');
                                 }])
                                 ->where('supir_id', $supir->id)
                                 ->whereIn('status', ['Menunggu', 'Berangkat'])
                                 ->orderBy('tanggal', 'asc')
                                 ->first();

            // 3. Hitung statistik
            if ($jadwalAktif) {
                $totalPenumpang = $jadwalAktif->tiket_count;
                $kursiKosong = $jadwalAktif->armada->total_kursi - $totalPenumpang;
            }
        }

        return view('driver.dashboard.index', compact('supir', 'jadwalAktif', 'totalPenumpang', 'kursiKosong'));
    }
}