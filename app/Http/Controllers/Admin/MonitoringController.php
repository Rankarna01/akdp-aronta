<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoringPerjalanan;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil jadwal yang sedang aktif untuk pilihan form
        $jadwalAktif = Jadwal::with(['rute', 'armada'])
            ->whereIn('status', ['Menunggu', 'Berangkat'])
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('admin.monitoring.index', compact('jadwalAktif'));
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $query = MonitoringPerjalanan::with(['jadwal.rute', 'jadwal.armada']);

        if (!empty($search)) {
            $query->where('lokasi_sekarang', 'LIKE', "%{$search}%")
                  ->orWhereHas('jadwal.armada', function($q) use ($search) {
                      $q->where('nama_bus', 'LIKE', "%{$search}%");
                  });
        }

        // Urutkan dari update lokasi terbaru
        $monitoring = $query->latest()->paginate(10);
        return response()->json($monitoring);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'lokasi_sekarang' => 'required|string|max:255',
            'status' => 'required|in:Persiapan,Di Perjalanan,Istirahat,Kendala,Tiba',
            'keterangan' => 'nullable|string',
        ]);

        MonitoringPerjalanan::create($request->all());

        // Otomatis ubah status jadwal utama jika bus sudah "Tiba"
        if ($request->status == 'Tiba') {
            Jadwal::where('id', $request->jadwal_id)->update(['status' => 'Selesai']);
        } elseif ($request->status == 'Di Perjalanan') {
            Jadwal::where('id', $request->jadwal_id)->update(['status' => 'Berangkat']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Update posisi perjalanan berhasil dicatat!'
        ]);
    }

    public function edit($id)
    {
        $monitoring = MonitoringPerjalanan::findOrFail($id);
        return response()->json($monitoring);
    }

    public function update(Request $request, $id)
    {
        $monitoring = MonitoringPerjalanan::findOrFail($id);

        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'lokasi_sekarang' => 'required|string|max:255',
            'status' => 'required|in:Persiapan,Di Perjalanan,Istirahat,Kendala,Tiba',
            'keterangan' => 'nullable|string',
        ]);

        $monitoring->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data monitoring berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $monitoring = MonitoringPerjalanan::findOrFail($id);
        $monitoring->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catatan posisi berhasil dihapus!'
        ]);
    }
}