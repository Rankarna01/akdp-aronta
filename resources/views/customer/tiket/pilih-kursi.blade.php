@extends('layouts.app-customer')

@section('title', 'Pilih Kursi')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 text-white shrink-0 relative shadow-md z-20">
    <div class="flex items-center gap-4 mb-4">
        <a href="javascript:history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Pilih Kursi</h1>
    </div>
    
    <div class="ml-12">
        <h2 class="text-sm font-bold">{{ $jadwal->armada->nama_bus }} &bull; {{ substr($jadwal->waktu_berangkat, 0, 5) }} WIB</h2>
        <p class="text-[11px] text-blue-200 mt-1">
            {{ $jadwal->rute->kota_asal }} <i class="fa-solid fa-arrow-right text-[8px] mx-1"></i> {{ $jadwal->rute->kota_tujuan }}
        </p>
    </div>
</div>

<div class="px-6 mt-6 pb-32">
    
    <div class="flex items-center justify-center gap-6 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded border-2 border-primary bg-white"></div>
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-gray-100 border-2 border-gray-300"></div>
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Terisi</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-success border-2 border-success"></div>
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Dipilih</span>
        </div>
    </div>

    <div class="max-w-[320px] mx-auto bg-white border-[6px] border-gray-200 rounded-[3rem] p-5 pt-8 pb-10 relative shadow-sm">
        
        <div class="grid grid-cols-[1fr_1fr_12px_1fr_1fr] gap-y-4 gap-x-2.5">
            @php
                $total = count($semuaKursi);
                $i = 0;
                $rowCount = 1;
            @endphp

            {{-- BARIS 1: [1] [2] | [Kosong] [Supir] --}}
            @if($total > 0) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
            @if($total > $i) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
            
            {{-- Garis Lorong Vertikal --}}
            <div class="flex items-center justify-center"><div class="w-[2px] h-full bg-gray-200 rounded-full opacity-60"></div></div>
            
            {{-- Ruang Kosong (Pintu) --}}
            <div></div>
            
            {{-- Kotak Supir --}}
            <div class="aspect-square w-full rounded-xl border-2 border-gray-300 bg-gray-100 text-gray-500 flex items-center justify-center text-[8px] uppercase tracking-wider font-bold shadow-sm">
                Supir
            </div>

            {{-- BARIS 2 - 5: Normal 2-2 --}}
            @while($i < $total && $rowCount <= 4) {{-- Maksimal 4 baris setelah baris pertama --}}
                {{-- Kiri 2 --}}
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                
                {{-- Garis Lorong Vertikal --}}
                <div class="flex items-center justify-center"><div class="w-[2px] h-full bg-gray-200 rounded-full opacity-60"></div></div>
                
                {{-- Kanan 2 --}}
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                
                @php $rowCount++; @endphp
            @endwhile

            {{-- BARIS 6: SISANYA DITUMPUK (Baris Paling Belakang) --}}
            @if($i < $total)
                <div class="col-span-5 flex justify-center gap-2.5 mt-2">
                    @while($i < $total)
                        <div class="flex-1 w-full max-w-[55px]">
                            @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi])
                        </div>
                        @php $i++; @endphp
                    @endwhile
                </div>
            @endif
        </div>
    </div>
</div>

<div class="fixed bottom-0 left-0 right-0 w-full max-w-md mx-auto bg-white border-t border-gray-100 p-4 px-6 z-50 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] flex items-center justify-between">
    <div>
        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Kursi Dipilih</p>
        <h3 id="selected-seat-text" class="text-sm font-bold text-primary">-</h3>
    </div>
    
    <form action="{{ route('customer.checkout.index') }}" method="GET" id="form-checkout">
        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
        <input type="hidden" name="kursi_id" id="input-kursi-id" value="">
        <input type="hidden" name="catatan_titik" value="{{ request('catatan_titik') }}">
        
        <button type="submit" id="btn-lanjutkan" disabled class="bg-gray-300 text-white font-bold py-3 px-8 rounded-xl transition shadow-sm cursor-not-allowed">
            Lanjutkan
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() { 
        $('nav.absolute.bottom-0').hide(); // Menyembunyikan menu navigasi bawah 
    });

    function selectSeat(element, kursiId, nomorKursi) {
        // 1. Reset semua kursi yang bisa diklik kembali ke state Awal (Border Biru, Background Putih)
        $('.seat-btn').removeClass('bg-success border-success text-white').addClass('bg-white border-primary text-primary');
        
        // 2. Ubah kursi yang baru saja diklik menjadi state Aktif (Background Hijau, Border Hijau)
        $(element).removeClass('bg-white border-primary text-primary').addClass('bg-success border-success text-white');
        
        // 3. Update data form checkout
        $('#selected-seat-text').text(nomorKursi);
        $('#input-kursi-id').val(kursiId);
        $('#btn-lanjutkan').removeClass('bg-gray-300 cursor-not-allowed').addClass('bg-primary hover:bg-blue-900 active:scale-95 shadow-lg shadow-primary/30').prop('disabled', false);
    }
</script>
@endpush