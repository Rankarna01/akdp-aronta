<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tiket;

class TiketSayaController extends Controller
{
    // Menampilkan halaman utama (Screen 9)
    public function index()
    {
        return view('customer.tiket-saya.index');
    }

    // Mengambil data tiket milik customer via AJAX berdasarkan tab status
    public function data(Request $request)
    {
        $status = $request->get('status', 'Semua');
        
        // Cari tiket berdasarkan user yang login melalui relasi nama/nik penumpang jika diperlukan,
        // namun idealnya tabel tiket dihubungkan langsung via user_id atau dicari yang memesan.
        // Di sini kita tarik berdasarkan tiket yang data nama pemesannya sesuai nama user atau kriteria pemesanan customer.
        $query = Tiket::with(['jadwal.rute', 'jadwal.armada', 'kursi', 'penumpang'])->where('user_id', Auth::id());

        // Filter data berdasarkan Tab Status sesuai Mockup
        if ($status === 'Aktif') {
            $query->where('status_tiket', 'Aktif');
        } elseif ($status === 'Selesai') {
            $query->where('status_tiket', 'Digunakan');
        } elseif ($status === 'Dibatalkan') {
            $query->where('status_tiket', 'Dibatalkan');
        }

        $tiket = $query->latest()->get();

        // Render HTML secara dinamis untuk performa mobile yang kencang
        $htmlContent = '';
        if ($tiket->count() === 0) {
            $htmlContent = '
                <div class="text-center py-16 text-secondary">
                    <i class="fa-solid fa-ticket-simple text-4xl block mb-2 opacity-30"></i>
                    <p class="text-xs">Tidak ada tiket dalam kategori ini</p>
                </div>';
        } else {
            foreach ($tiket as $item) {
                // Tentukan badge status tiket
                $badgeColor = 'bg-gray-100 text-gray-600';
                $statusText = $item->status_pembayaran;

                if ($item->status_tiket === 'Dibatalkan') {
                    $badgeColor = 'bg-danger/10 text-danger border border-danger/20';
                    $statusText = 'Dibatalkan';
                } elseif ($item->status_tiket === 'Digunakan') {
                    $badgeColor = 'bg-success/10 text-success border border-success/20';
                    $statusText = 'Selesai';
                } else {
                    if ($item->status_pembayaran === 'Paid') {
                        $badgeColor = 'bg-warning/10 text-warning border border-warning/20';
                        $statusText = 'Menunggu Verifikasi';
                    } elseif ($item->status_pembayaran === 'Pending') {
                        $badgeColor = 'bg-warning/10 text-warning border border-warning/20';
                        $statusText = 'Pending';
                    } else {
                        $badgeColor = 'bg-gray-100 text-gray-600 border border-gray-200';
                        $statusText = 'Belum Bayar';
                    }
                }

                $tanggalFormat = \Carbon\Carbon::parse($item->jadwal->tanggal)->translatedFormat('D, d M Y');

                $htmlContent .= '
                <div onclick="viewTicketDetail('.$item->id.')" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col gap-3 relative overflow-hidden active:scale-[0.99] transition cursor-pointer">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="font-mono text-xs font-bold text-gray-800 tracking-wider">'.$item->kode_tiket.'</span>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full '.$badgeColor.'">'.$statusText.'</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Armada</h4>
                        <p class="text-sm font-bold text-gray-800 mt-0.5"><i class="fa-solid fa-bus text-primary mr-1 text-xs"></i> '.$item->jadwal->armada->nama_bus.'</p>
                        
                        <div class="flex items-center gap-2 mt-2 font-bold text-gray-800 text-sm">
                            <span>'.$item->jadwal->rute->kota_asal.'</span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-gray-300"></i>
                            <span>'.$item->jadwal->rute->kota_tujuan.'</span>
                        </div>
                        <p class="text-[10px] text-secondary mt-1"><i class="fa-regular fa-calendar mr-1"></i> '.$tanggalFormat.' &bull; '.substr($item->jadwal->waktu_berangkat, 0, 5).' WIB</p>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-50 pt-3 mt-1">
                        <div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase">Kursi</p>
                            <p class="text-xs font-bold text-gray-800">'.$item->kursi->nomor_kursi.'</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 font-bold uppercase">Total</p>
                            <p class="text-xs font-bold text-primary">Rp '.number_format($item->harga, 0, ',', '.').'</p>
                        </div>
                    </div>
                </div>';
            }
        }

        return response()->json(['html' => $htmlContent]);
    }

    public function show($id)
    {
        $tiket = Tiket::with(['jadwal.rute', 'jadwal.armada', 'kursi', 'penumpang'])
                      ->where('user_id', Auth::id())
                      ->findOrFail($id);
        
        $tiket->tanggal_indo = \Carbon\Carbon::parse($tiket->jadwal->tanggal)->translatedFormat('l, d F Y');
        $tiket->jam_indo = substr($tiket->jadwal->waktu_berangkat, 0, 5) . ' WIB';
        $tiket->harga_indo = 'Rp ' . number_format($tiket->harga, 0, ',', '.');

        return response()->json($tiket);
    }

    public function cetak($id)
    {
        $tiket = Tiket::with(['jadwal.rute', 'jadwal.armada', 'kursi', 'penumpang'])
                      ->where('user_id', Auth::id())
                      ->findOrFail($id);
        
        // Pastikan hanya tiket milik customer (atau nama yang sesuai) yang bisa dicetak,
        // namun untuk simulasi ini kita langsung lemparkan datanya.
        return view('customer.tiket-saya.cetak', compact('tiket'));
    }
}