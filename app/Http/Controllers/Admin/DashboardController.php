<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Armada;
use App\Models\Supir;
use App\Models\Tiket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now('Asia/Jakarta');
        
        $totalArmada = Armada::count();
        $armadaBeroperasi = Armada::where('status', 'Aktif')->count();
        $armadaMaintenance = Armada::where('status', 'Maintenance')->count();
        $totalSupir = Supir::count();
        
        $tiketHariIni = Tiket::whereDate('created_at', $now->toDateString())->count();
        
        // Pendapatan bulan ini
        $pendapatanBulanIni = Tiket::whereMonth('created_at', $now->month)
                                   ->whereYear('created_at', $now->year)
                                   ->where('status_pembayaran', 'Paid')
                                   ->sum('harga');
                                   
        // Data penjualan tiket 7 hari terakhir
        $salesLabels = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $salesLabels[] = $date->translatedFormat('D');
            
            $count = Tiket::whereDate('created_at', $date->toDateString())
                          ->where('status_pembayaran', 'Paid')
                          ->count();
            $salesData[] = $count;
        }

        return view('admin.dashboard.index', compact(
            'totalArmada', 
            'armadaBeroperasi', 
            'armadaMaintenance', 
            'totalSupir', 
            'tiketHariIni', 
            'pendapatanBulanIni',
            'salesLabels',
            'salesData'
        ));
    }
}