@extends('layouts.app-driver')

@section('title', 'Daftar Penumpang')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 shrink-0 text-white relative">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('driver.dashboard') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Daftar Penumpang</h1>
    </div>

    @if($jadwalAktif)
        <div class="ml-12">
            <div class="flex items-center gap-2 text-base font-bold">
                <span>{{ $jadwalAktif->rute->kota_asal }}</span>
                <i class="fa-solid fa-arrow-right text-xs opacity-60"></i>
                <span>{{ $jadwalAktif->rute->kota_tujuan }}</span>
            </div>
            <p class="text-[11px] text-blue-200 mt-1">
                {{ substr($jadwalAktif->waktu_berangkat, 0, 5) }} WIB &bull; {{ \Carbon\Carbon::parse($jadwalAktif->tanggal)->translatedFormat('l, d M Y') }}
            </p>
        </div>
    @endif
</div>

<div class="px-6 -mt-5 relative z-10">
    <div class="relative w-full shadow-sm rounded-xl">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </span>
        <input type="text" id="search-penumpang" class="input-modern w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs placeholder-gray-400" placeholder="Cari nama / kursi / no. tiket...">
    </div>
</div>

<div class="px-6 mt-4 grid grid-cols-3 gap-3 text-center">
    <div class="bg-white border border-gray-50 rounded-xl p-3 shadow-sm">
        <h3 id="count-total" class="text-base font-bold text-gray-800">0</h3>
        <p class="text-[10px] text-secondary font-medium mt-0.5">Total</p>
    </div>
    <div class="bg-white border border-gray-50 rounded-xl p-3 shadow-sm">
        <h3 id="count-checkin" class="text-base font-bold text-success">0</h3>
        <p class="text-[10px] text-success font-bold mt-0.5">Check-In</p>
    </div>
    <div class="bg-white border border-gray-50 rounded-xl p-3 shadow-sm">
        <h3 id="count-belum" class="text-base font-bold text-danger">0</h3>
        <p class="text-[10px] text-danger font-bold mt-0.5">Belum</p>
    </div>
</div>

<div class="px-6 mt-5 space-y-3" id="passenger-list-container">
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Load data pertama kali halaman dibuka
        loadManifestData();

        // Fitur search dengan delay (Debounce) agar hemat bandwidth smartphone supir
        let searchTimer;
        $('#search-penumpang').on('keyup', function() {
            clearTimeout(searchTimer);
            let keyword = $(this).val();
            searchTimer = setTimeout(function() {
                loadManifestData(keyword);
            }, 300);
        });
    });

    // Fungsi fetch data utama manifest
    function loadManifestData(searchKeyword = '') {
        $.ajax({
            url: "{{ route('driver.penumpang.data') }}",
            type: "GET",
            data: { search: searchKeyword },
            success: function(response) {
                // Update text angka statistik di atas
                $('#count-total').text(response.total);
                $('#count-checkin').text(response.checkin);
                $('#count-belum').text(response.belum);

                // Update list element
                $('#passenger-list-container').html(response.html);
            }
        });
    }

    // Fungsi merubah status masuk penumpang pas didepan pintu bus
    function toggleCheckin(tiketId, currentStatus) {
        let alertText = currentStatus 
            ? 'Batalkan check-in untuk penumpang ini?' 
            : 'Konfirmasi penumpang telah memasuki armada bus?';
            
        let confirmBtnText = currentStatus ? 'Ya, Batalkan' : 'Ya, Check-In!';

        Swal.fire({
            title: 'Verifikasi Penumpang',
            text: alertText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: confirmBtnText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/driver/penumpang/toggle-checkin/${tiketId}`,
                    type: "POST",
                    success: function(response) {
                        if (response.success) {
                            // Toast Notifikasi Sukses Kecil di Atas Layar HP
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            
                            // Ambil ulang data keyword yang ada di input search sekarang
                            let currentKeyword = $('#search-penumpang').val();
                            loadManifestData(currentKeyword);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush