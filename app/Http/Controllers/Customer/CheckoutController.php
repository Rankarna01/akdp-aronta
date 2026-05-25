<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Penumpang;
use App\Models\Tiket;
use App\Models\Pembayaran;

class CheckoutController extends Controller
{
    // Halaman Form Checkout (Screen 6)
    public function index(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'kursi_id' => 'required|exists:kursi,id',
        ]);

        $jadwal = Jadwal::with(['rute', 'armada'])->findOrFail($request->jadwal_id);
        $kursi = Kursi::findOrFail($request->kursi_id);
        
        // Tangkap catatan titik naik dari URL
        $catatan_titik = $request->query('catatan_titik');

        return view('customer.checkout.index', compact('jadwal', 'kursi', 'catatan_titik'));
    }

    // Proses Simpan Tiket & Penumpang
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'kursi_id' => 'required|exists:kursi,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'catatan_titik' => 'nullable|string|max:255' // Validasi field baru
        ]);

        DB::beginTransaction();
        try {
            // 1. Cek keamanan: Pastikan kursi belum dibooking orang lain di detik yang sama
            $cekKursi = Tiket::where('jadwal_id', $request->jadwal_id)
                             ->where('kursi_id', $request->kursi_id)
                             ->where('status_tiket', '!=', 'Dibatalkan')
                             ->lockForUpdate()
                             ->first();

            if ($cekKursi) {
                return response()->json(['success' => false, 'message' => 'Mohon maaf, kursi baru saja dipesan oleh orang lain. Silakan pilih kursi lain.'], 422);
            }

            // 2. Cek apakah NIK Penumpang sudah ada di database, jika belum buat baru
            $penumpang = Penumpang::firstOrCreate(
                ['nik' => $request->nik],
                [
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]
            );

            // 3. Ambil harga dari jadwal
            $jadwal = Jadwal::findOrFail($request->jadwal_id);

            // 4. Generate Kode Tiket
            $kodeTiket = 'AKDP' . date('ymd') . strtoupper(Str::random(4));

            // 5. Buat Tiket (Simpan dengan Catatan Titik)
            $tiket = Tiket::create([
                'kode_tiket' => $kodeTiket,
                'jadwal_id' => $jadwal->id,
                'penumpang_id' => $penumpang->id,
                'kursi_id' => $request->kursi_id,
                'catatan_titik' => $request->catatan_titik, // Pastikan ini masuk
                'harga' => $jadwal->harga_tiket,
                'status_pembayaran' => 'Unpaid',
                'status_tiket' => 'Aktif',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil di-booking!',
                'redirect' => route('customer.checkout.pembayaran', $tiket->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    // Halaman Upload Bukti Transfer (Screen 7)
    public function pembayaran($tiket_id)
    {
        $tiket = Tiket::with(['jadwal.rute', 'kursi'])->findOrFail($tiket_id);

        // Keamanan: Jika sudah lunas/pending, jangan boleh upload lagi
        if ($tiket->status_pembayaran !== 'Unpaid' && $tiket->status_pembayaran !== 'Failed') {
            return redirect()->route('customer.home')->with('error', 'Pembayaran sedang diproses atau sudah lunas.');
        }

        return view('customer.checkout.pembayaran', compact('tiket'));
    }

    // Proses Simpan Bukti Pembayaran
    public function prosesPembayaran(Request $request, $tiket_id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maks 5MB
            'keterangan' => 'nullable|string'
        ]);

        $tiket = Tiket::findOrFail($tiket_id);

        DB::beginTransaction();
        try {
            // Upload File
            $buktiPath = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

            // Catat Pembayaran
            Pembayaran::create([
                'tiket_id' => $tiket->id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar' => $tiket->harga,
                'bukti_transfer' => $buktiPath,
                'status' => 'Pending', // Menunggu verifikasi admin
            ]);

            // Update status tiket jadi Pending
            $tiket->update(['status_pembayaran' => 'Pending']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah file.'], 500);
        }
    }
}