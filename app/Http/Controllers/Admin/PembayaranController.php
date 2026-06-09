<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\Tiket;

class PembayaranController extends Controller
{
    // Menampilkan halaman utama Verifikasi Pembayaran
    public function index()
    {
        return view('admin.pembayaran.index');
    }

    // Mengambil data via AJAX
    public function data(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status'); // Filter tab status (Semua, Pending, Lunas, Ditolak)

        // Tarik data pembayaran beserta relasi tiket, penumpang, dan jadwal
        $query = Pembayaran::with(['tiket.penumpang', 'tiket.jadwal.rute']);

        // Filter berdasarkan status
        if (!empty($status) && $status !== 'Semua') {
            $query->where('status', $status);
        }

        // Pencarian berdasarkan kode tiket atau nama penumpang
        if (!empty($search)) {
            $query->whereHas('tiket', function($q) use ($search) {
                $q->where('kode_tiket', 'LIKE', "%{$search}%")
                  ->orWhereHas('penumpang', function($q2) use ($search) {
                      $q2->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Urutkan yang Pending (Menunggu Verifikasi) di paling atas
        $pembayaran = $query->orderByRaw("FIELD(status, 'Pending', 'Lunas', 'Ditolak')")->latest()->paginate(10);
        
        return response()->json($pembayaran);
    }

    // Fungsi untuk Verifikasi (Approve) atau Menolak (Reject) Pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $tiket = Tiket::findOrFail($pembayaran->tiket_id);

        DB::beginTransaction();
        try {
            if ($request->action === 'approve') {
                // Ubah status pembayaran menjadi Lunas
                $pembayaran->update(['status' => 'Lunas']);
                // Aktifkan tiket pelanggan menjadi Paid dan langsung Selesai (Digunakan)
                $tiket->update(['status_pembayaran' => 'Paid', 'status_tiket' => 'Digunakan']);
                $message = 'Pembayaran berhasil diverifikasi! Tiket pelanggan telah selesai.';
            } else {
                // Ubah status pembayaran menjadi Ditolak
                $pembayaran->update(['status' => 'Ditolak']);
                // Ubah status tiket pelanggan menjadi Failed agar mereka bisa upload ulang
                $tiket->update(['status_pembayaran' => 'Failed']);
                $message = 'Bukti pembayaran ditolak.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Pembayaran update error: ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}