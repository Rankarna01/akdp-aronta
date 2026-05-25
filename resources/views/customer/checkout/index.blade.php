@extends('layouts.app-customer')

@section('title', 'Checkout Pemesanan')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 text-white shrink-0 relative shadow-md z-20">
    <div class="flex items-center gap-4 mb-4">
        <a href="javascript:history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Checkout Pemesanan</h1>
    </div>
</div>

<div class="px-6 mt-6 pb-32">
    
    <h3 class="text-sm font-bold text-gray-800 mb-3">Detail Perjalanan</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
        <div class="pl-2 flex justify-between items-start mb-3">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bus text-primary"></i>
                <span class="text-xs font-bold text-gray-800">{{ $jadwal->armada->nama_bus }}</span>
            </div>
            <span class="text-xs font-bold text-gray-800">{{ substr($jadwal->waktu_berangkat, 0, 5) }} WIB</span>
        </div>
        <div class="pl-2">
            <p class="text-xs font-bold text-gray-800 mb-0.5">{{ $jadwal->rute->kota_asal }} <i class="fa-solid fa-arrow-right text-[10px] text-gray-400 mx-1"></i> {{ $jadwal->rute->kota_tujuan }}</p>
            <p class="text-[10px] text-secondary">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</p>
            
            @if(!empty($catatan_titik))
                <div class="mt-2 bg-blue-50 border border-blue-100 rounded-lg p-2 inline-block">
                    <p class="text-[10px] text-primary font-bold"><i class="fa-solid fa-location-dot mr-1"></i> Naik di: {{ $catatan_titik }}</p>
                </div>
            @endif
        </div>
    </div>

    <h3 class="text-sm font-bold text-gray-800 mb-3">Detail Penumpang</h3>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form id="form-checkout-submit" onsubmit="submitCheckout(event)">
            <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
            <input type="hidden" name="kursi_id" value="{{ $kursi->id }}">
            <input type="hidden" name="catatan_titik" value="{{ $catatan_titik }}">

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" required value="{{ Auth::user()->name }}" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold text-gray-800">
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">NIK (KTP)</label>
                <input type="number" name="nik" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold text-gray-800" placeholder="Masukkan 16 digit NIK">
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">No. Handphone</label>
                <input type="number" name="no_hp" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold text-gray-800" placeholder="Contoh: 08123456789">
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                <div class="flex items-center gap-6 mt-1">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-xs font-semibold text-gray-700">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="Perempuan" required class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-xs font-semibold text-gray-700">Perempuan</span>
                    </label>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-600">Kursi Dipilih</span>
                <span class="bg-success/10 text-success border border-success/20 px-3 py-1 rounded-lg text-xs font-bold">{{ $kursi->nomor_kursi }}</span>
            </div>
        </form>
    </div>
</div>

<div class="fixed bottom-0 left-0 right-0 w-full max-w-md mx-auto bg-white border-t border-gray-100 p-4 px-6 z-50 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] flex items-center justify-between">
    <div>
        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">Total Pembayaran</p>
        <h3 class="text-base font-bold text-primary">Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</h3>
    </div>
    
    <button type="submit" form="form-checkout-submit" class="bg-primary hover:bg-blue-900 text-white text-sm font-bold py-3 px-6 rounded-xl transition active:scale-95 shadow-lg shadow-primary/30">
        Lanjut Pembayaran
    </button>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('nav.absolute.bottom-0').hide(); // Sembunyikan bottom menu global
    });

    function submitCheckout(e) {
        e.preventDefault();
        let formData = $('#form-checkout-submit').serialize();

        $.ajax({
            url: "{{ route('customer.checkout.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(jqxhr) {
                let errorMsg = 'Terjadi kesalahan.';
                if(jqxhr.status === 422) {
                    if(jqxhr.responseJSON.message) {
                        errorMsg = jqxhr.responseJSON.message;
                    } else {
                        errorMsg = 'Pastikan semua form (NIK, Nama, No HP) terisi dengan benar.';
                    }
                }
                Swal.fire({ icon: 'error', title: 'Gagal!', text: errorMsg });
            }
        });
    }
</script>
@endpush