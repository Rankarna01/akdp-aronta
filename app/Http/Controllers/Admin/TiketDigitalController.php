<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TiketDigitalController extends Controller
{
    public function index()
    {
        return view('admin.tiket-digital.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        
        // Hanya ambil tiket yang sudah Paid (Lunas) dan Aktif
        $query = Tiket::with(['jadwal.rute', 'jadwal.armada', 'penumpang', 'kursi'])
                      ->where('status_pembayaran', 'Paid')
                      ->where('status_tiket', 'Aktif');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('kode_tiket', 'LIKE', "%{$search}%")
                  ->orWhereHas('penumpang', function($q2) use ($search) {
                      $q2->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        $tiket = $query->latest()->paginate(10);
        return response()->json($tiket);
    }

    public function cetak($id)
    {
        // Ambil data tiket spesifik untuk dicetak
        $tiket = Tiket::with(['jadwal.rute', 'jadwal.armada', 'penumpang', 'kursi'])->findOrFail($id);
        
        // Halaman ini memiliki layout khusus cetak (tanpa sidebar & navbar)
        return view('admin.tiket-digital.cetak', compact('tiket'));
    }
}