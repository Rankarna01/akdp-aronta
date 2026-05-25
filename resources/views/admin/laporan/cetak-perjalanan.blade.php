<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Perjalanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; color: #333; }
        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            .no-print { display: none !important; }
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; font-size: 12px; }
        th { background-color: #f8fafc; font-weight: 600; text-transform: uppercase; text-align: left;}
        .text-center { text-align: center; }
    </style>
</head>
<body class="p-8">

    <div class="no-print flex justify-end mb-6">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak Laporan
        </button>
    </div>

    <div class="border-b-2 border-gray-800 pb-4 mb-6 text-center">
        <h1 class="text-2xl font-bold uppercase tracking-wide">PT Aronta Citra Persada</h1>
        <p class="text-sm mt-1">Laporan Operasional Jadwal & Perjalanan Bus AKDP</p>
        <p class="text-xs text-gray-500 mt-1">
            Periode: {{ $filter['start_date'] ? \Carbon\Carbon::parse($filter['start_date'])->format('d M Y') : 'Awal' }} s/d {{ $filter['end_date'] ? \Carbon\Carbon::parse($filter['end_date'])->format('d M Y') : 'Sekarang' }} | Status: {{ $filter['status'] }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal & Waktu</th>
                <th width="20%">Rute Perjalanan</th>
                <th width="20%">Armada Bus (Plat)</th>
                <th width="15%">Nama Supir</th>
                <th class="text-center" width="15%">Total Penumpang</th>
                <th class="text-center" width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}<br>
                        <span style="font-size: 10px; color: #666;">{{ substr($item->waktu_berangkat, 0, 5) }} WIB</span>
                    </td>
                    <td>{{ $item->rute->kota_asal }} - {{ $item->rute->kota_tujuan }}</td>
                    <td>{{ $item->armada->nama_bus }} <br><span style="font-size:10px;">({{ $item->armada->plat_nomor }})</span></td>
                    <td>{{ $item->supir->user->name }}</td>
                    <td class="text-center">{{ $item->tiket_count }} / {{ $item->armada->total_kursi }} Kursi</td>
                    <td class="text-center">{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada data perjalanan ditemukan pada filter tersebut.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-12 flex justify-end text-sm">
        <div class="text-center">
            <p>Medan, {{ date('d F Y') }}</p>
            <p class="mt-16 font-bold underline">Administrator</p>
            <p class="text-xs text-gray-500">PT Aronta Citra Persada</p>
        </div>
    </div>

    <script>
        // Opsional: Otomatis memicu dialog print saat halaman dimuat
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>