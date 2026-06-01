<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;

class HomeController extends Controller
{
    public function index()
    {
        $jadwalTerdekat = Jadwal::with(['rute', 'armada'])
            ->withCount(['tiket' => function ($query) {
                $query->where('status_tiket', '!=', 'Dibatalkan');
            }])
            ->where('status', 'Menunggu')
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->limit(5)
            ->get();

        return view('customer.home.index', compact('jadwalTerdekat'));
    }
}