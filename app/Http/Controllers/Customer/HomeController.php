<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil 5 jadwal terdekat mulai dari hari ini yang statusnya masih 'Menunggu' atau 'Berangkat'
        // Hitung juga jumlah tiket yang sudah dipesan (tidak dibatalkan) untuk mengetahui sisa kursi
        $jadwalTerdekat = Jadwal::with(['rute', 'armada'])
            ->withCount(['tiket' => function ($query) {
                // Asumsi tiket yang dipesan = Paid/Pending (mengurangi slot kursi)
                $query->where('status_tiket', '!=', 'Dibatalkan');
            }])
            ->whereDate('tanggal', '>=', Carbon::today())
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_berangkat', 'asc')
            ->limit(5)
            ->get();

        return view('customer.home.index', compact('user', 'jadwalTerdekat'));
    }
}