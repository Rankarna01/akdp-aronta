<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Manifest Penumpang</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; color: #333; }
        @media print {
            @page { size: A4 portrait; margin: 15mm; }
            .no-print { display: none !important; }
        }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; font-size: 11px; }
        th { background-color: #f8fafc; font-weight: 600; text-transform: uppercase; text-align: left;}
        .text-center { text-align: center; }
    </style>
</head>
<body class="p-8">

    <div class="no-print flex justify-end mb-6">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
            Cetak Manifest PDF
        </button>
    </div>

    <div class="border-b-2 border-gray-800 pb-4 mb-6">
        <h1 class="text-2xl font-bold uppercase tracking-wide text-center">PT Aronta Citra Persada</h1>
        <p class="text-sm mt-1 text-center font-semibold">MANIFEST PENUMPANG</p>
        
        @if($filter['jadwal_info'])
            <div class="mt-6 flex justify-between text-xs border border-gray-200 p-4 bg-gray-50 rounded">
                <div>
                    <p><strong>Rute:</strong> {{ $filter['jadwal_info']->rute->kota_asal }} - {{ $filter['jadwal_info']->rute->kota_tujuan }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($filter['jadwal_info']->tanggal)->format('d F Y') }}</p>
                    <p><strong>Waktu Berangkat:</strong> {{ substr($filter['jadwal_info']->waktu_berangkat, 0, 5) }} WIB</p>
                </div>
                <div>
                    <p><strong>Armada Bus:</strong> {{ $filter['jadwal_info']->armada->nama_bus }}</p>
                    <p><strong>Plat Nomor:</strong> {{ $filter['jadwal_info']->armada->plat_nomor }}</p>
                    <p><strong>Supir Bertugas:</strong> {{ $filter['jadwal_info']->supir->user->name }}</p>
                </div>
            </div>
        @else
            <p class="text-xs text-gray-500 mt-2 text-center">
                Filter Tanggal: {{ $filter['tanggal'] ? \Carbon\Carbon::parse($filter['tanggal'])->format('d/m/Y') : 'Semua Waktu' }} (Semua Armada)
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="10%">Kursi</th>
                <th width="25%">Nama Penumpang</th>
                <th width="15%">Jenis Kelamin</th>
                <th width="20%">No. HP</th>
                <th width="25%">NIK</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penumpang as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold text-lg">{{ $item->kursi->nomor_kursi }}</td>
                    <td class="font-semibold">{{ $item->penumpang->nama }}</td>
                    <td>{{ $item->penumpang->jenis_kelamin }}</td>
                    <td>{{ $item->penumpang->no_hp }}</td>
                    <td style="font-family: monospace;">{{ $item->penumpang->nik }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Tidak ada penumpang terdaftar (Tiket belum Lunas/Valid).</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-12 flex justify-between text-sm">
        <div class="text-center">
            <p>Supir Bertugas</p>
            <p class="mt-16 font-bold underline">{{ $filter['jadwal_info'] ? $filter['jadwal_info']->supir->user->name : '( ............................ )' }}</p>
        </div>
        <div class="text-center">
            <p>Medan, {{ date('d F Y') }}</p>
            <p class="mt-16 font-bold underline">Petugas Loket / Admin</p>
        </div>
    </div>

</body>
</html>