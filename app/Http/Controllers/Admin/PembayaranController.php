<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        // Hanya ambil tiket yang belum lunas atau masih pending untuk pilihan dropdown
        $tiketPending = Tiket::with('penumpang')
            ->whereIn('status_pembayaran', ['Unpaid', 'Pending'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pembayaran.index', compact('tiketPending'));
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = Pembayaran::with(['tiket.penumpang']);

        if (!empty($search)) {
            $query->whereHas('tiket', function($q) use ($search) {
                $q->where('kode_tiket', 'LIKE', "%{$search}%")
                  ->orWhereHas('penumpang', function($q2) use ($search) {
                      $q2->where('nama', 'LIKE', "%{$search}%");
                  });
            })->orWhere('metode_pembayaran', 'LIKE', "%{$search}%");
        }

        $pembayaran = $query->latest()->paginate(10);
        return response()->json($pembayaran);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tiket_id' => 'required|exists:tiket,id',
            'metode_pembayaran' => 'required|string|max:100',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Lunas,Ditolak',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti_transfer')) {
                $buktiPath = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');
            }

            Pembayaran::create([
                'tiket_id' => $request->tiket_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status' => $request->status,
                'bukti_transfer' => $buktiPath,
                'tanggal_bayar' => now(),
            ]);

            // Update status pembayaran di tabel Tiket secara otomatis
            if ($request->status == 'Lunas') {
                Tiket::where('id', $request->tiket_id)->update(['status_pembayaran' => 'Paid']);
            } elseif ($request->status == 'Pending') {
                Tiket::where('id', $request->tiket_id)->update(['status_pembayaran' => 'Pending']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dicatat!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with('tiket')->findOrFail($id);
        return response()->json($pembayaran);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'tiket_id' => 'required|exists:tiket,id',
            'metode_pembayaran' => 'required|string|max:100',
            'jumlah_bayar' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Lunas,Ditolak',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = $pembayaran->bukti_transfer;
            if ($request->hasFile('bukti_transfer')) {
                if ($buktiPath) {
                    Storage::disk('public')->delete($buktiPath);
                }
                $buktiPath = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');
            }

            $pembayaran->update([
                'tiket_id' => $request->tiket_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status' => $request->status,
                'bukti_transfer' => $buktiPath,
            ]);

            // Update Sinkronisasi Tiket
            if ($request->status == 'Lunas') {
                Tiket::where('id', $request->tiket_id)->update(['status_pembayaran' => 'Paid']);
            } elseif ($request->status == 'Ditolak') {
                Tiket::where('id', $request->tiket_id)->update(['status_pembayaran' => 'Failed']);
            } else {
                Tiket::where('id', $request->tiket_id)->update(['status_pembayaran' => 'Pending']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data pembayaran berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if ($pembayaran->bukti_transfer) {
            Storage::disk('public')->delete($pembayaran->bukti_transfer);
        }
        $pembayaran->delete();

        return response()->json(['success' => true, 'message' => 'Data pembayaran berhasil dihapus!']);
    }
}