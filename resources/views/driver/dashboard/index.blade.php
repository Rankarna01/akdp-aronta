@extends('layouts.app-driver')

@section('title', 'Dashboard')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-12 pb-24 px-6 relative">
    <div class="flex justify-between items-center mb-6 text-white">
        <div>
            <h1 class="text-xl font-bold">Halo, {{ Auth::user()->name }} 👋</h1>
            <p class="text-xs text-blue-200 mt-1">Selamat bertugas hari ini</p>
        </div>
        <button class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center relative backdrop-blur-sm border border-white/20">
            <i class="fa-regular fa-bell text-lg"></i>
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-danger rounded-full"></span>
        </button>
    </div>
</div>

<div class="px-6 -mt-16 relative z-10">
    <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold mb-1">Bus yang digunakan</p>
            @if($jadwalAktif)
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-bus text-primary"></i> {{ $jadwalAktif->armada->nama_bus }}
                </h2>
                <p class="text-xs text-secondary mt-1 ml-6 flex items-center gap-1 font-mono">
                    <i class="fa-regular fa-id-card"></i> Pintu: {{ $jadwalAktif->armada->nomor_pintu ?? '-' }} | Plat: {{ $jadwalAktif->armada->plat_nomor }}
                </p>
            @else
                <h2 class="text-lg font-bold text-gray-800">Tidak ada penugasan</h2>
            @endif
        </div>
        <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center text-primary text-2xl">
            <i class="fa-solid fa-van-shuttle"></i>
        </div>
    </div>
</div>

<div class="px-6 mt-6 space-y-6 pb-24">
    
    @if($jadwalAktif)
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-4">Jadwal Aktif Hari Ini</h3>
            
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $jadwalAktif->rute->kota_asal }}</h2>
                <i class="fa-solid fa-arrow-right text-gray-300"></i>
                <h2 class="text-2xl font-bold text-gray-800">{{ $jadwalAktif->rute->kota_tujuan }}</h2>
            </div>
            
            <div class="flex items-center gap-2 text-sm text-secondary mb-2">
                <i class="fa-regular fa-calendar text-gray-400 w-4"></i>
                <span>{{ \Carbon\Carbon::parse($jadwalAktif->tanggal)->translatedFormat('l, d M Y') }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-secondary mb-6 border-b border-gray-100 pb-6">
                <i class="fa-regular fa-clock text-gray-400 w-4"></i>
                <span>{{ substr($jadwalAktif->waktu_berangkat, 0, 5) }} WIB</span>
            </div>

            <div class="flex items-center justify-between mb-6">
                <span class="text-sm font-semibold text-gray-600">Status Perjalanan</span>
                <span class="bg-warning/10 text-warning px-3 py-1 rounded-full text-xs font-bold border border-warning/20 flex items-center gap-1">
                    {{ $jadwalAktif->status === 'Menunggu' ? 'Boarding' : 'Di Perjalanan' }} <span class="w-2 h-2 bg-warning rounded-full animate-pulse ml-1"></span>
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-primary mb-1">{{ $totalPenumpang }}</h3>
                    <p class="text-xs text-secondary flex items-center gap-1"><i class="fa-solid fa-users text-gray-400"></i> Penumpang</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-success mb-1">{{ $kursiKosong }}</h3>
                    <p class="text-xs text-secondary flex items-center gap-1"><i class="fa-solid fa-chair text-gray-400"></i> Kursi Kosong</p>
                </div>
            </div>

            <h3 class="text-sm font-bold text-gray-800 mb-3">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('driver.status.index') }}" class="bg-primary hover:bg-blue-900 text-white rounded-2xl p-4 shadow-lg shadow-primary/30 flex flex-col items-center justify-center gap-2 transition text-center active:scale-95">
                    <i class="fa-regular fa-paper-plane text-2xl mb-1"></i>
                    <span class="text-xs font-medium leading-tight">Update Status<br>Perjalanan</span>
                </a>
                <a href="{{ route('driver.penumpang.index') }}" class="bg-white hover:bg-gray-50 text-primary border border-gray-100 rounded-2xl p-4 shadow-sm flex flex-col items-center justify-center gap-2 transition text-center active:scale-95">
                    <i class="fa-solid fa-user-check text-2xl mb-1"></i>
                    <span class="text-xs font-medium leading-tight">Lihat Daftar<br>Penumpang</span>
                </a>
            </div>
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-mug-hot text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Anda Sedang Istirahat</h3>
            <p class="text-sm text-secondary px-4">Belum ada jadwal perjalanan yang ditugaskan kepada Anda untuk hari ini.</p>
        </div>
    @endif

</div>
@endsection