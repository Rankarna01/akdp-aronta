@extends('layouts.app-customer')

@section('title', 'Beranda')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-12 pb-20 px-6 relative shrink-0">
    <div class="flex justify-between items-center text-white">
        <div>
            <h1 class="text-xl font-bold">Halo, Selamat datang 👋</h1>
            <p class="text-xs text-blue-200 mt-1">Mau ke mana hari ini?</p>
        </div>
        <button class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center relative backdrop-blur-sm border border-white/20">
            <i class="fa-regular fa-bell text-lg"></i>
        </button>
    </div>
</div>

<div class="px-6 -mt-10 relative z-10">
    <div class="flex items-center justify-between mb-3 px-1">
        <h2 class="text-sm font-bold text-white">Jadwal Keberangkatan</h2>
        <a href="#" class="text-[10px] text-blue-100 hover:text-white font-medium transition">Lihat Semua</a>
    </div>

    <div class="space-y-4">
        @forelse($jadwalTerdekat as $item)
            @php
                $kursiTerisi = $item->tiket_count;
                $kursiTersedia = $item->armada->total_kursi - $kursiTerisi;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex gap-4">
                <div class="flex-1">
                    <p class="text-xs font-bold text-gray-800">{{ substr($item->waktu_berangkat, 0, 5) }} WIB</p>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-sm font-bold text-gray-800">{{ $item->rute->kota_asal }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px] text-gray-400"></i>
                        <span class="text-sm font-bold text-gray-800">{{ $item->rute->kota_tujuan }}</span>
                    </div>
                    <p class="text-[10px] text-secondary mt-1">{{ $item->armada->nama_bus }}</p>
                    <p class="text-primary font-bold text-sm mt-2">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</p>
                    <p class="text-[10px] font-semibold text-success mt-1">Tersedia {{ $kursiTersedia }} kursi</p>
                </div>
                <div class="w-24 flex flex-col justify-center items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center text-primary text-3xl mb-2">
                        <i class="fa-solid fa-bus"></i>
                    </div>
                    <a href="#" class="w-full bg-primary hover:bg-blue-900 text-white text-center text-[10px] font-medium py-1.5 rounded-lg transition">Pesan</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <i class="fa-solid fa-calendar-xmark text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs text-secondary">Belum ada jadwal keberangkatan dalam waktu dekat.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="px-6 mt-8 mb-6">
    <h3 class="text-sm font-bold text-gray-800 mb-4 px-1">Keuntungan Kami</h3>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-4">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-800">Aman & Nyaman</h4>
                <p class="text-[10px] text-secondary">Armada terawat dengan fasilitas terbaik.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-full bg-success/10 text-success flex items-center justify-center">
                <i class="fa-regular fa-clock text-sm"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-800">Berangkat Tepat Waktu</h4>
                <p class="text-[10px] text-secondary">Jadwal keberangkatan yang selalu on-time.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-full bg-warning/10 text-warning flex items-center justify-center">
                <i class="fa-solid fa-tags text-sm"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-800">Harga Terjangkau</h4>
                <p class="text-[10px] text-secondary">Harga tiket kompetitif untuk semua rute.</p>
            </div>
        </div>
    </div>
</div>
@endsection