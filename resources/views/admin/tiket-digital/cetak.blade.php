<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket | {{ $tiket->kode_tiket }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#1e3a8a', secondary: '#475569' }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f1f5f9; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        @media print {
            body { background-color: #ffffff; }
            .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
            @page { margin: 0; size: auto; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 font-sans">

    <div class="max-w-md w-full mx-4">
        
        <div class="mb-6 flex justify-end gap-3 no-print">
            <button onclick="window.close()" class="px-4 py-2 bg-white text-secondary border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 text-sm font-medium transition">Tutup</button>
            <button onclick="window.print()" class="px-4 py-2 bg-primary text-white rounded-lg shadow-sm hover:bg-blue-900 text-sm font-medium transition"><i class="fa-solid fa-download mr-2"></i> Simpan PDF</button>
        </div>

        <div class="print-area bg-white rounded-[2rem] shadow-xl overflow-hidden border border-gray-100">
            
            <div class="bg-primary p-6 text-white text-center">
                <h1 class="text-xl font-bold tracking-wide">AKDPSys E-Ticket</h1>
                <p class="text-xs text-blue-200 mt-1">PT Aronta Citra Persada</p>
            </div>

            <div class="p-6 border-b border-gray-100 border-dashed">
                <div class="flex justify-between items-center mb-6">
                    <span class="bg-gray-100 text-gray-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $tiket->jadwal->armada->nama_bus }}
                    </span>
                    <span class="font-mono text-xs font-bold text-gray-500">{{ $tiket->kode_tiket }}</span>
                </div>

                <div class="flex items-center justify-between text-center mb-6">
                    <div class="flex-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Asal</p>
                        <h2 class="text-2xl font-bold text-primary">{{ $tiket->jadwal->rute->kota_asal }}</h2>
                    </div>
                    <div class="px-2 text-gray-300">
                        <i class="fa-solid fa-bus text-xl"></i>
                        <div class="w-full border-t-2 border-dashed border-gray-300 mt-1"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Tujuan</p>
                        <h2 class="text-2xl font-bold text-primary">{{ $tiket->jadwal->rute->kota_tujuan }}</h2>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Tanggal</p>
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($tiket->jadwal->tanggal)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Jam Berangkat</p>
                        <p class="text-sm font-bold text-gray-800">{{ substr($tiket->jadwal->waktu_berangkat, 0, 5) }} WIB</p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-100 border-dashed grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Penumpang</p>
                    <p class="text-sm font-bold text-gray-800">{{ $tiket->penumpang->nama }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Kursi</p>
                    <p class="text-lg font-bold text-primary">{{ $tiket->kursi->nomor_kursi }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Status Tiket</p>
                    <p class="text-xs font-bold text-success">{{ $tiket->status_tiket === 'Digunakan' ? 'Selesai' : $tiket->status_tiket }}</p>
                </div>
            </div>

            <div class="p-8 bg-white text-center flex flex-col items-center justify-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Kode Tiket</p>
                <h1 class="text-4xl font-extrabold tracking-widest text-primary mb-4 font-mono">{{ $tiket->kode_tiket }}</h1>
                <p class="text-[10px] text-gray-500 max-w-[250px]">Simpan E-Tiket ini dan tunjukkan Kode Tiket kepada petugas saat menaiki armada.</p>
            </div>
            
            <div class="h-4 bg-primary w-full"></div>
        </div>

    </div>

    <script>
        // Memaksa dialog cetak PDF langsung muncul otomatis saat halaman terbuka
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>