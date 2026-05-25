<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        return view('admin.log-aktivitas.index');
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = LogAktivitas::with('user');

        if (!empty($search)) {
            $query->where('keterangan', 'LIKE', "%{$search}%")
                  ->orWhere('modul', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('role', 'LIKE', "%{$search}%");
                  });
        }

        // Urutkan dari aktivitas yang paling baru terjadi
        $logs = $query->latest()->paginate(15);
        
        return response()->json($logs);
    }

    // Fungsi untuk menghapus seluruh log (Clear All)
    public function clearAll()
    {
        LogAktivitas::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Seluruh riwayat log aktivitas berhasil dibersihkan!'
        ]);
    }
}