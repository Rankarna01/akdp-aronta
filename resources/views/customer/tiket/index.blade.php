@extends('layouts.app-customer')

@section('title', 'Cari Tiket')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-20 px-6 relative shrink-0 shadow-md">
    <div class="flex items-center gap-4 text-white">
        <a href="{{ route('customer.home') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Cari Tiket</h1>
    </div>
</div>

<div class="px-6 -mt-12 relative z-10">
    <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-6 relative">
        <form action="{{ route('customer.tiket.search') }}" method="GET">
            
            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Berangkat</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-primary pointer-events-none">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <input type="date" name="tanggal" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="input-modern w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-gray-800 shadow-inner focus:bg-white">
                </div>
            </div>

            <div class="mb-5 relative border border-gray-100 rounded-2xl p-4 shadow-sm bg-gray-50 hover:bg-white transition-colors focus-within:bg-white focus-within:ring-2 focus-within:ring-primary/20">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Rute Perjalanan</label>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-map-location-dot text-primary text-lg"></i>
                    <select name="rute_id" required class="w-full text-sm font-bold text-gray-800 bg-transparent border-none p-0 focus:ring-0 appearance-none cursor-pointer">
                        <option value="">-- Pilih Rute Tersedia --</option>
                        @foreach($rute as $rt)
                            <option value="{{ $rt->id }}">{{ $rt->kota_asal }} - {{ $rt->kota_tujuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                    Titik Naik / Tunggu <span class="text-[8px] bg-blue-100 text-primary px-1.5 py-0.5 rounded ml-1">Opsional</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-primary pointer-events-none">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input type="text" name="catatan_titik" class="input-modern w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-800 shadow-inner focus:bg-white" placeholder="Contoh: Simpang Pos, Indomaret Amplas...">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary/30 transition active:scale-[0.98] flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Jadwal Bus
            </button>
        </form>
    </div>
</div>
@endsection