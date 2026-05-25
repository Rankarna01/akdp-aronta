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
    
    <div class="flex items-center justify-center gap-5 mb-8">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded border-2 border-gray-300 bg-white"></div>
            <span class="text-[10px] font-bold text-gray-500">Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-primary border-2 border-primary"></div>
            <span class="text-[10px] font-bold text-gray-500">Terisi</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-success border-2 border-success"></div>
            <span class="text-[10px] font-bold text-gray-500">Dipilih</span>
        </div>
    </div>

    <div class="max-w-[340px] mx-auto bg-white border-[6px] border-gray-300 rounded-[3.5rem] p-6 pt-10 relative shadow-sm">
        
        <div class="grid grid-cols-5 gap-y-4 gap-x-3">
            @php
                $total = count($semuaKursi);
                $i = 0;
            @endphp

            {{-- BARIS 1: [Kursi 1] [Kursi 2] [Lorong] [Kosong] [Supir] --}}
            @if($total > 0) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
            @if($total > $i) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
            
            <div class="w-full flex items-center justify-center"><div class="w-1 h-full bg-gray-200 rounded-full opacity-50"></div></div>
            <div></div>
            <div class="aspect-square flex flex-col items-center justify-center rounded-xl border-[3px] border-gray-300 bg-gray-100 text-gray-500 font-bold text-[8px] shadow-sm">
                <i class="fa-solid fa-steering-wheel text-xl mb-1"></i>Supir
            </div>

            {{-- BARIS 2 - 5 --}}
            @php $rowCount = 2; @endphp
            @while($i < $total && $rowCount <= 5)
                @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                
                <div class="w-full flex items-center justify-center"><div class="w-1 h-full bg-gray-200 rounded-full opacity-50"></div></div>
                
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                @if($i < $total) @include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi]) @php $i++; @endphp @else <div></div> @endif
                @php $rowCount++; @endphp
            @endwhile

            {{-- BARIS 6: SISANYA DITUMPUK --}}
            @if($i < $total)
                <div class="col-span-5 flex justify-between items-center gap-2 mt-1">
                    @while($i < $total)
                        <div class="flex-1 w-full">@include('components.seat-block', ['kursi' => $semuaKursi[$i], 'kursiTerisi' => $kursiTerisi])</div>
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
    $(document).ready(function() { $('nav.absolute.bottom-0').hide(); });

    function selectSeat(element, kursiId, nomorKursi) {
        $('.seat-btn').removeClass('bg-success border-success text-white').addClass('bg-white border-gray-300 text-gray-600');
        $(element).removeClass('bg-white border-gray-300 text-gray-600').addClass('bg-success border-success text-white');
        $('#selected-seat-text').text(nomorKursi);
        $('#input-kursi-id').val(kursiId);
        $('#btn-lanjutkan').removeClass('bg-gray-300 cursor-not-allowed').addClass('bg-primary hover:bg-blue-900 active:scale-95 shadow-lg shadow-primary/30').prop('disabled', false);
    }
</script>
@endpush