<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursi;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KursiController extends Controller
{
    public function index()
    {
        $armada = Armada::where('status', 'Aktif')->get();
        return view('admin.kursi.index', compact('armada'));
    }

    public function data(Request $request)
    {
        $search = $request->get('search');
        $armadaId = $request->get('armada_id'); // Tangkap filter armada_id

        $query = Kursi::with('armada');

        // Filter berdasarkan bus yang sedang dibuka
        if (!empty($armadaId)) {
            $query->where('armada_id', $armadaId);
        }

        // Pencarian spesifik nomor kursi
        if (!empty($search)) {
            $query->where('nomor_kursi', 'LIKE', "%{$search}%");
        }

        // Urutkan nomor kursi secara natural (1, 2, 3, 10, bukan 1, 10, 2)
        $kursi = $query->orderByRaw('CAST(nomor_kursi AS UNSIGNED) ASC')->paginate(10);
                       
        return response()->json($kursi);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armada,id',
            'jumlah_kursi' => 'required|integer|min:10|max:60',
        ]);

        Kursi::where('armada_id', $request->armada_id)->delete();

        $dataKursi = [];
        for ($i = 1; $i <= $request->jumlah_kursi; $i++) {
            $dataKursi[] = [
                'armada_id' => $request->armada_id,
                'nomor_kursi' => (string)$i,
                'status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Kursi::insert($dataKursi);

        return response()->json([
            'success' => true,
            'message' => $request->jumlah_kursi . ' Kursi berhasil di-generate secara otomatis!'
        ]);
    }

    public function getLayout($armada_id)
    {
        $kursi = Kursi::where('armada_id', $armada_id)
            ->orderByRaw('CAST(nomor_kursi AS UNSIGNED) ASC')
            ->get();
            
        return response()->json($kursi);
    }

    public function edit($id)
    {
        $kursi = Kursi::findOrFail($id);
        return response()->json($kursi);
    }

    public function update(Request $request, $id)
    {
        $kursi = Kursi::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Aktif,Non-Aktif',
            'nomor_kursi' => [
                'required', 'string', 'max:10',
                Rule::unique('kursi')->where(function ($query) use ($kursi) {
                    return $query->where('armada_id', $kursi->armada_id);
                })->ignore($id)
            ],
        ]);

        $kursi->update([
            'nomor_kursi' => $request->nomor_kursi,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Kursi berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $kursi = Kursi::findOrFail($id);
        $kursi->delete();

        return response()->json(['success' => true, 'message' => 'Data Kursi berhasil dihapus!']);
    }
}