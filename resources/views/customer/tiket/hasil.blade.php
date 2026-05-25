@extends('layouts.app-customer')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="bg-primary pt-10 pb-6 px-6 shrink-0 relative shadow-md z-20">
    <div class="flex items-center gap-4 text-white mb-5">
        <a href="{{ route('customer.tiket.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Hasil Pencarian</h1>
    </div>
    
    <div class="bg-white/10 border border-white/20 rounded-xl p-3 backdrop-blur-sm text-white">
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-sm font-bold">{{ $ruteTerpilih->kota_asal }}</h2>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-70"></i>
            <h2 class="text-sm font-bold">{{ $ruteTerpilih->kota_tujuan }}</h2>
        </div>
        <p class="text-[10px] text-blue-100">
            {{ \Carbon\Carbon::parse($params['tanggal'])->translatedFormat('l, d M Y') }}
            @if(!empty($params['catatan_titik']))
                &bull; Naik di: <span class="font-bold text-white">{{ $params['catatan_titik'] }}</span>
            @endif
        </p>
    </div>
</div>

<div class="px-6 mt-6 pb-6 space-y-4">
    
    @forelse($jadwal as $item)
        @php
            $kursiTerisi = $item->tiket_count;
            $kursiTersedia = $item->armada->total_kursi - $kursiTerisi;
            $isPenuh = $kursiTersedia <= 0;
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col relative {{ $isPenuh ? 'opacity-60 grayscale-[50%]' : '' }}">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isPenuh ? 'bg-gray-400' : 'bg-primary' }}"></div>
            
            <div class="p-5 pl-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ substr($item->waktu_berangkat, 0, 5) }}</h3>
                    <p class="text-sm font-bold text-primary">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</p>
                </div>

                <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-xs mb-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold mb-0.5">Asal</p>
                        <p class="font-bold text-gray-700">{{ $item->rute->kota_asal }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold mb-0.5">Tujuan</p>
                        <p class="font-bold text-gray-700">{{ $item->rute->kota_tujuan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-semibold mb-0.5">Armada Bus</p>
                        <p class="font-bold text-gray-700">{{ $item->armada->nama_bus }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50/80 px-6 py-3 border-t border-gray-100 flex items-center justify-between">
                @if($isPenuh)
                    <span class="text-[10px] font-bold text-danger"><i class="fa-solid fa-circle-xmark mr-1"></i> Kursi Habis</span>
                    <button disabled class="bg-gray-300 text-white text-[10px] font-bold px-4 py-1.5 rounded-lg cursor-not-allowed">Penuh</button>
                @else
                    <span class="text-[10px] font-bold text-success"><i class="fa-solid fa-chair mr-1"></i> Tersedia {{ $kursiTersedia }} kursi</span>
                    <a href="{{ route('customer.tiket.pilih-kursi', $item->id) }}?catatan_titik={{ urlencode($params['catatan_titik'] ?? '') }}" class="bg-primary hover:bg-blue-900 text-white text-[10px] font-bold px-4 py-1.5 rounded-lg transition active:scale-95 flex items-center gap-1 shadow-sm shadow-primary/20">
                        Pilih Kursi <i class="fa-solid fa-chevron-right text-[8px]"></i>
                    </a>
                @endif
            </div>
        </div>
        
    @empty
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-magnifying-glass-location text-3xl text-primary opacity-60"></i>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-1">Jadwal Tidak Ditemukan</h3>
            <p class="text-xs text-secondary px-4">Maaf, tiket untuk rute dan tanggal tersebut belum tersedia atau sudah penuh. Coba cari di tanggal lain.</p>
            <a href="{{ route('customer.tiket.index') }}" class="mt-6 text-sm font-bold text-primary hover:underline">Kembali Mencari</a>
        </div>
    @endforelse

</div>
@endsection