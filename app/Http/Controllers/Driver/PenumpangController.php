<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supir;
use App\Models\Jadwal;
use App\Models\Tiket;

class PenumpangController extends Controller
{
    // Menampilkan halaman utama manifest penumpang
    public function index()
    {
        $supir = Supir::where('user_id', Auth::id())->first();
        
        // Ambil jadwal perjalanan supir yang berstatus aktif saat ini
        $jadwalAktif = Jadwal::with(['rute'])
            ->where('supir_id', $supir->id)
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('tanggal', 'asc')
            ->first();

        return view('driver.penumpang.index', compact('jadwalAktif'));
    }

    // Mengambil list data penumpang secara realtime menggunakan AJAX (Mendukung Search)
    public function data(Request $request)
    {
        $supir = Supir::where('user_id', Auth::id())->first();
        
        $jadwalAktif = Jadwal::where('supir_id', $supir->id)
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->first();

        if (!$jadwalAktif) {
            return response()->json([
                'html' => '', 'total' => 0, 'checkin' => 0, 'belum' => 0
            ]);
        }

        // Query tiket penumpang lunas milik jadwal ini
        $query = Tiket::with(['penumpang', 'kursi'])
            ->where('jadwal_id', $jadwalAktif->id)
            ->where('status_pembayaran', 'Paid');

        // Fitur pencarian dari input search mockup
        $search = $request->get('search');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('kode_tiket', 'LIKE', "%{$search}%")
                  ->orWhereHas('penumpang', function($q2) use ($search) {
                      $q2->where('nama', 'LIKE', "%{$search}%");
                  })->orWhereHas('kursi', function($q3) use ($search) {
                      $q3->where('nomor_kursi', 'LIKE', "%{$search}%");
                  });
            });
        }

        $tiket = $query->get();

        // Hitung statistik counter atas sesuai mockup
        $total = $tiket->count();
        $checkin = $tiket->where('status_tiket', 'Digunakan')->count();
        $belum = $tiket->where('status_tiket', 'Aktif')->count();

        // Render HTML Card List secara dinamis agar performa list kencang di device mobile
        $htmlContent = '';
        if ($total === 0) {
            $htmlContent = '
                <div class="text-center py-12 text-secondary">
                    <i class="fa-solid fa-users-slash text-3xl block mb-2 opacity-40"></i>
                    <p class="text-xs">Tidak ada data penumpang ditemukan</p>
                </div>';
        } else {
            foreach ($tiket as $item) {
                $isCheckIn = $item->status_tiket === 'Digunakan';
                $badgeColor = $isCheckIn ? 'bg-success text-white' : 'bg-danger/10 text-danger';
                $badgeText = $isCheckIn ? 'Check-In' : 'Belum Check-In';
                
                $htmlContent .= '
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center justify-between transition active:scale-[0.99]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-50 border border-gray-100 rounded-full flex items-center justify-center text-gray-400 text-sm">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">'.$item->penumpang->nama.'</h4>
                            <p class="text-xs text-primary font-semibold mt-0.5">Kursi '.$item->kursi->nomor_kursi.'</p>
                            <p class="text-[10px] text-gray-400 font-mono mt-0.5">No. Tiket: '.$item->kode_tiket.'</p>
                        </div>
                    </div>
                    <button onclick="toggleCheckin('.$item->id.', '.$isCheckIn.')" class="px-3 py-1.5 rounded-lg text-[10px] font-bold tracking-wide transition shadow-sm '.$badgeColor.'">
                        '.$badgeText.'
                    </button>
                </div>';
            }
        }

        return response()->json([
            'html' => $htmlContent,
            'total' => $total,
            'checkin' => $checkin,
            'belum' => $belum
        ]);
    }

    // Fungsi AJAX untuk merubah status penumpang pas masuk pintu bus
    public function toggleCheckin($id)
    {
        $tiket = Tiket::findOrFail($id);
        
        // Balikkan status tiketnya
        $newStatus = $tiket->status_tiket === 'Aktif' ? 'Digunakan' : 'Aktif';
        $tiket->update(['status_tiket' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus === 'Digunakan' ? 'Penumpang berhasil Check-In!' : 'Check-In dibatalkan.'
        ]);
    }
}