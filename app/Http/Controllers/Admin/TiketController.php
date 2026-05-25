<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Jadwal;
use App\Models\Penumpang;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TiketController extends Controller
{
    public function index()
    {
        // Hanya tampilkan jadwal yang belum selesai/batal
        $jadwal = Jadwal::with(['rute', 'armada'])
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('tanggal', 'asc')
            ->get();
        $penumpang = Penumpang::orderBy('nama', 'asc')->get();
        
        return view('admin.tiket.index', compact('jadwal', 'penumpang'));
    }

    // Fungsi canggih untuk mengambil kursi yang KOSONG pada jadwal tertentu
    public function getKursiAvailable($jadwal_id, Request $request)
    {
        $jadwal = Jadwal::findOrFail($jadwal_id);
        $tiket_id = $request->query('tiket_id'); // Jika sedang mode Edit

        // Cari ID kursi yang sudah dibooking di jadwal ini
        $queryBooked = Tiket::where('jadwal_id', $jadwal_id)
                            ->where('status_tiket', '!=', 'Dibatalkan');
        
        // Jika sedang diedit, abaikan kursi tiket itu sendiri (agar bisa dipilih lagi)
        if ($tiket_id) {
            $queryBooked->where('id', '!=', $tiket_id);
        }

        $bookedKursi = $queryBooked->pluck('kursi_id');

        // Ambil kursi milik armada bus tersebut yang belum dibooking
        $kursiTersedia = Kursi::where('armada_id', $jadwal->armada_id)
                              ->where('status', 'Aktif')
                              ->whereNotIn('id', $bookedKursi)
                              ->get();

        return response()->json([
            'harga' => $jadwal->harga_tiket,
            'kursi' => $kursiTersedia
        ]);
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = Tiket::with(['jadwal.rute', 'jadwal.armada', 'penumpang', 'kursi']);

        if (!empty($search)) {
            $query->where('kode_tiket', 'LIKE', "%{$search}%")
                  ->orWhereHas('penumpang', function($q) use ($search) {
                      $q->where('nama', 'LIKE', "%{$search}%");
                  });
        }

        $tiket = $query->latest()->paginate(10);
        return response()->json($tiket);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'penumpang_id' => 'required|exists:penumpang,id',
            'kursi_id' => 'required|exists:kursi,id',
            'harga' => 'required|numeric',
            'status_pembayaran' => 'required|in:Unpaid,Pending,Paid,Failed',
            'status_tiket' => 'required|in:Aktif,Digunakan,Dibatalkan',
        ]);

        // Cek manual jika ada percobaan bypass kursi yang sudah dibooking
        $cekBooking = Tiket::where('jadwal_id', $request->jadwal_id)
                           ->where('kursi_id', $request->kursi_id)
                           ->where('status_tiket', '!=', 'Dibatalkan')
                           ->first();
        if ($cekBooking) {
            return response()->json(['success' => false, 'message' => 'Gagal! Kursi tersebut sudah dipesan.'], 422);
        }

        // Generate Kode Tiket (ACP + Nomor Pintu + Nomor Bangku)
        $jadwal = Jadwal::with('armada')->findOrFail($request->jadwal_id);
        $nomorPintu = $jadwal->armada->nomor_pintu ?? '000';
        $kursi = Kursi::findOrFail($request->kursi_id);
        $nomorBangku = preg_replace('/[^0-9A-Za-z]/', '', $kursi->nomor_kursi);
        $kodeTiket = 'ACP' . $nomorPintu . $nomorBangku;
        
        $data = $request->all();
        $data['kode_tiket'] = $kodeTiket;

        Tiket::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dipesan!'
        ]);
    }

    public function edit($id)
    {
        $tiket = Tiket::findOrFail($id);
        return response()->json($tiket);
    }

    public function update(Request $request, $id)
    {
        $tiket = Tiket::findOrFail($id);

        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'penumpang_id' => 'required|exists:penumpang,id',
            'kursi_id' => 'required|exists:kursi,id',
            'harga' => 'required|numeric',
            'status_pembayaran' => 'required|in:Unpaid,Pending,Paid,Failed',
            'status_tiket' => 'required|in:Aktif,Digunakan,Dibatalkan',
        ]);

        $tiket->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data tiket berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dihapus!'
        ]);
    }
}