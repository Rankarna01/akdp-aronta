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
            ->where(function($q) {
                $q->whereDate('tanggal', '>', now()->toDateString())
                  ->orWhere(function($q2) {
                      $q2->whereDate('tanggal', '=', now()->toDateString())
                         ->whereTime('waktu_berangkat', '>', now()->toTimeString());
                  });
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_berangkat', 'asc')
            ->limit(5)
            ->get();

        return view('customer.home.index', compact('jadwalTerdekat'));
    }
}