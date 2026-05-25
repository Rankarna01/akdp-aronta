<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class LaporanController extends Controller
{
    // Menampilkan halaman Laporan Perjalanan
    public function perjalanan()
    {
        return view('admin.laporan.perjalanan');
    }

    // Mengambil data via AJAX dengan Filter
    public function perjalananData(Request $request)
    {
        // Ambil jadwal beserta relasinya, dan hitung jumlah tiket yang statusnya Paid (Lunas)
        $query = Jadwal::with(['rute', 'armada', 'supir.user'])
                       ->withCount(['tiket' => function ($query) {
                           $query->where('status_pembayaran', 'Paid')
                                 ->where('status_tiket', '!=', 'Dibatalkan');
                       }]);

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan status operasional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporan = $query->orderBy('tanggal', 'desc')->paginate(10);
        return response()->json($laporan);
    }

    // Menampilkan halaman khusus cetak (PDF View)
    public function cetakPerjalanan(Request $request)
    {
        $query = Jadwal::with(['rute', 'armada', 'supir.user'])
                       ->withCount(['tiket' => function ($query) {
                           $query->where('status_pembayaran', 'Paid')
                                 ->where('status_tiket', '!=', 'Dibatalkan');
                       }]);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jadwal = $query->orderBy('tanggal', 'desc')->get();
        
        $filter = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? 'Semua Status'
        ];

        return view('admin.laporan.cetak-perjalanan', compact('jadwal', 'filter'));
    }

    public function transaksi()
    {
        return view('admin.laporan.transaksi');
    }

    public function transaksiData(Request $request)
    {
        // Panggil relasi ke tiket, penumpang, dan rute
        $query = Pembayaran::with(['tiket.penumpang', 'tiket.jadwal.rute']);

        // Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Filter Status Pembayaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Metode Pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', 'LIKE', "%{$request->metode_pembayaran}%");
        }

        // Kalkulasi Total Pendapatan (Hanya yang statusnya Lunas pada filter terkait)
        $totalPendapatan = (clone $query)->where('status', 'Lunas')->sum('jumlah_bayar');

        $laporan = $query->orderBy('tanggal_bayar', 'desc')->paginate(10);
        
        return response()->json([
            'laporan' => $laporan,
            'total_pendapatan' => $totalPendapatan
        ]);
    }

    public function cetakTransaksi(Request $request)
    {
        $query = Pembayaran::with(['tiket.penumpang', 'tiket.jadwal.rute']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', 'LIKE', "%{$request->metode_pembayaran}%");
        }

        $transaksi = $query->orderBy('tanggal_bayar', 'desc')->get();
        $totalPendapatan = (clone $query)->where('status', 'Lunas')->sum('jumlah_bayar');

        $filter = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? 'Semua Status',
            'metode_pembayaran' => $request->metode_pembayaran ?? 'Semua Metode'
        ];

        return view('admin.laporan.cetak-transaksi', compact('transaksi', 'totalPendapatan', 'filter'));
    }

    public function penumpang()
    {
        // Ambil data jadwal untuk opsi filter
        $jadwal = Jadwal::with(['rute', 'armada'])->orderBy('tanggal', 'desc')->get();
        return view('admin.laporan.penumpang', compact('jadwal'));
    }

    public function penumpangData(Request $request)
    {
        // Hanya ambil penumpang yang tiketnya sudah Lunas dan Aktif
        $query = \App\Models\Tiket::with(['penumpang', 'jadwal.rute', 'jadwal.armada', 'kursi'])
                      ->where('status_pembayaran', 'Paid')
                      ->where('status_tiket', 'Aktif');

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->where('tanggal', $request->tanggal);
            });
        }

        // Filter Spesifik Jadwal (Bus & Rute tertentu)
        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
        }

        $laporan = $query->latest()->paginate(10);
        
        return response()->json($laporan);
    }

    public function cetakPenumpang(Request $request)
    {
        $query = \App\Models\Tiket::with(['penumpang', 'jadwal.rute', 'jadwal.armada', 'kursi'])
                      ->where('status_pembayaran', 'Paid')
                      ->where('status_tiket', 'Aktif');

        $jadwalInfo = null;

        if ($request->filled('tanggal')) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->where('tanggal', $request->tanggal);
            });
        }

        if ($request->filled('jadwal_id')) {
            $query->where('jadwal_id', $request->jadwal_id);
            $jadwalInfo = Jadwal::with(['rute', 'armada', 'supir.user'])->find($request->jadwal_id);
        }

        $penumpang = $query->get(); // Ambil semua data tanpa paginate

        $filter = [
            'tanggal' => $request->tanggal,
            'jadwal_info' => $jadwalInfo // Untuk header kop surat jika filter by jadwal
        ];

        return view('admin.laporan.cetak-penumpang', compact('penumpang', 'filter'));
    }
}