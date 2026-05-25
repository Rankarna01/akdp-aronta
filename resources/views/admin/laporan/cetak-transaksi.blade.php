<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; color: #333; }
        @media print {
            @page { size: A4 portrait; margin: 15mm; }
            .no-print { display: none !important; }
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; font-size: 11px; }
        th { background-color: #f8fafc; font-weight: 600; text-transform: uppercase; text-align: left;}
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body class="p-8">

    <div class="no-print flex justify-end mb-6">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak PDF
        </button>
    </div>

    <div class="border-b-2 border-gray-800 pb-4 mb-6 text-center">
        <h1 class="text-2xl font-bold uppercase tracking-wide">PT Aronta Citra Persada</h1>
        <p class="text-sm mt-1">Laporan Pemasukan & Transaksi Tiket</p>
        <p class="text-xs text-gray-500 mt-1">
            Periode: {{ $filter['start_date'] ? \Carbon\Carbon::parse($filter['start_date'])->format('d/m/Y') : 'Awal' }} - {{ $filter['end_date'] ? \Carbon\Carbon::parse($filter['end_date'])->format('d/m/Y') : 'Sekarang' }} <br>
            Metode: {{ $filter['metode_pembayaran'] }} | Status: {{ $filter['status'] }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal Bayar</th>
                <th width="15%">Kode Tiket</th>
                <th width="20%">Nama Penumpang</th>
                <th width="15%">Metode</th>
                <th class="text-center" width="10%">Status</th>
                <th class="text-right" width="20%">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') }}</td>
                    <td style="font-family: monospace;">{{ $item->tiket->kode_tiket }}</td>
                    <td>{{ $item->tiket->penumpang->nama }}</td>
                    <td>{{ $item->metode_pembayaran }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                    <td class="text-right">{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="6" class="text-right py-3 uppercase tracking-wider text-xs">Total Pendapatan (Hanya Lunas) :</td>
                <td class="text-right text-sm">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-12 flex justify-end text-sm">
        <div class="text-center">
            <p>Medan, {{ date('d F Y') }}</p>
            <p class="mt-16 font-bold underline">Staff Keuangan</p>
            <p class="text-xs text-gray-500">PT Aronta Citra Persada</p>
        </div>
    </div>

</body>
</html>