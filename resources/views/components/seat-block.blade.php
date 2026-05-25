@php
    $isBooked = in_array($kursi->id, $kursiTerisi);
@endphp

@if($isBooked)
    <div class="aspect-square rounded-xl border-[3px] border-primary bg-primary text-white flex items-center justify-center text-sm font-bold shadow-sm opacity-90 cursor-not-allowed">
        {{ $kursi->nomor_kursi }}
    </div>
@else
    <button type="button" 
            onclick="selectSeat(this, {{ $kursi->id }}, '{{ $kursi->nomor_kursi }}')"
            class="seat-btn aspect-square rounded-xl border-[3px] border-gray-300 bg-white text-gray-600 flex items-center justify-center text-sm font-bold shadow-sm transition active:scale-95 hover:border-success hover:text-success">
        {{ $kursi->nomor_kursi }}
    </button>
@endif