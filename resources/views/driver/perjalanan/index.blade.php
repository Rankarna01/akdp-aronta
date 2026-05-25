@extends('layouts.app-driver')

@section('title', 'Perjalanan Saya')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 relative shrink-0">
    <div class="flex items-center gap-4 text-white">
        <a href="{{ route('driver.dashboard') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Perjalanan Saya</h1>
    </div>
</div>

<div class="px-6 -mt-4 relative z-10 shrink-0">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1.5 flex text-center">
        <button id="tab-btn-hari-ini" onclick="switchTab('hari-ini')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-primary text-white shadow-md">
            Jadwal Hari Ini
        </button>
        <button id="tab-btn-riwayat" onclick="switchTab('riwayat')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all text-secondary hover:bg-gray-50">
            Riwayat
        </button>
    </div>
</div>

<div class="px-6 mt-6 pb-6">

    <div id="tab-hari-ini" class="space-y-4 transition-opacity duration-300">
        @forelse($jadwalHariIni as $item)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden flex flex-col gap-3">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            {{ substr($item->waktu_berangkat, 0, 5) }} WIB
                        </h3>
                        <p class="text-sm font-bold text-gray-900 mt-2">{{ $item->rute->kota_asal }} <i class="fa-solid fa-arrow-right text-gray-300 mx-1 text-[10px]"></i> {{ $item->rute->kota_tujuan }}</p>
                        <p class="text-[10px] text-secondary mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <span class="bg-blue-50 text-primary border border-blue-100 text-[10px] font-bold px-2.5 py-1 rounded-full">Aktif</span>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2 pt-3 border-t border-gray-50 text-xs">
                    <div class="text-gray-500">Bus <span class="float-right font-medium text-gray-800">{{ $item->armada->nama_bus }}</span></div>
                    <div class="text-gray-500">Plat <span class="float-right font-mono font-bold text-gray-800">{{ $item->armada->plat_nomor }}</span></div>
                    <div class="text-gray-500 col-span-2">
                        Status 
                        <span class="float-right {{ $item->status == 'Menunggu' ? 'text-warning bg-warning/10' : 'text-primary bg-primary/10' }} font-bold px-2 py-0.5 rounded text-[10px]">
                            {{ $item->status == 'Menunggu' ? 'Boarding' : 'Di Perjalanan' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-secondary">Tidak ada jadwal perjalanan untuk hari ini.</p>
            </div>
        @endforelse
    </div>

    <div id="tab-riwayat" class="space-y-4 hidden opacity-0 transition-opacity duration-300">
        @forelse($riwayat as $item)
            @php
                $badgeClass = $item->status == 'Selesai' ? 'bg-success/10 text-success border-success/20' : 'bg-danger/10 text-danger border-danger/20';
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden opacity-75 hover:opacity-100 transition-opacity">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $item->status == 'Selesai' ? 'bg-success' : 'bg-danger' }}"></div>

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-600 flex items-center gap-2">
                            {{ substr($item->waktu_berangkat, 0, 5) }} WIB
                        </h3>
                        <p class="text-sm font-bold text-gray-700 mt-2">{{ $item->rute->kota_asal }} <i class="fa-solid fa-arrow-right text-gray-300 mx-1 text-[10px]"></i> {{ $item->rute->kota_tujuan }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</p>
                    </div>
                    <span class="border text-[10px] font-bold px-2.5 py-1 rounded-full {{ $badgeClass }}">{{ $item->status }}</span>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2 pt-3 border-t border-gray-50 text-xs opacity-80">
                    <div class="text-gray-500">Bus <span class="float-right font-medium text-gray-800">{{ $item->armada->nama_bus }}</span></div>
                    <div class="text-gray-500">Plat <span class="float-right font-mono text-gray-800">{{ $item->armada->plat_nomor }}</span></div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <i class="fa-solid fa-clock-rotate-left text-4xl text-gray-300 mb-3"></i>
                <p class="text-sm text-secondary">Belum ada riwayat perjalanan.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchTab(tabName) {
        const btnHariIni = document.getElementById('tab-btn-hari-ini');
        const btnRiwayat = document.getElementById('tab-btn-riwayat');
        const contentHariIni = document.getElementById('tab-hari-ini');
        const contentRiwayat = document.getElementById('tab-riwayat');

        if (tabName === 'hari-ini') {
            // Ubah Style Tombol
            btnHariIni.className = "flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-primary text-white shadow-md";
            btnRiwayat.className = "flex-1 py-2 text-xs font-bold rounded-lg transition-all text-secondary hover:bg-gray-50";
            
            // Ubah Konten
            contentRiwayat.classList.add('hidden', 'opacity-0');
            contentHariIni.classList.remove('hidden');
            setTimeout(() => contentHariIni.classList.remove('opacity-0'), 50);

        } else {
            // Ubah Style Tombol
            btnRiwayat.className = "flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-primary text-white shadow-md";
            btnHariIni.className = "flex-1 py-2 text-xs font-bold rounded-lg transition-all text-secondary hover:bg-gray-50";
            
            // Ubah Konten
            contentHariIni.classList.add('hidden', 'opacity-0');
            contentRiwayat.classList.remove('hidden');
            setTimeout(() => contentRiwayat.classList.remove('opacity-0'), 50);
        }
    }
</script>
@endpush