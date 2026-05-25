@php
    $isBooked = in_array($kursi->id, $kursiTerisi);
@endphp

@if($isBooked)
    <div class="aspect-square w-full rounded-xl border-[3px] border-gray-300 bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-bold shadow-sm cursor-not-allowed opacity-80" title="Sudah Dipesan">
        {{ $kursi->nomor_kursi }}
    </div>
@else
    <button type="button" 
            onclick="selectSeat(this, {{ $kursi->id }}, '{{ $kursi->nomor_kursi }}')"
            class="seat-btn aspect-square w-full rounded-xl border-[3px] border-primary bg-white text-primary flex items-center justify-center text-sm font-bold shadow-sm transition-all active:scale-95 hover:bg-blue-50" title="Tersedia">
        {{ $kursi->nomor_kursi }}
    </button>
@endif