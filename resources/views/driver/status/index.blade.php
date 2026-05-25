@extends('layouts.app-driver')

@section('title', 'Update Status')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 text-white shrink-0 relative">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('driver.dashboard') }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Update Status</h1>
    </div>
</div>

<div class="px-6 mt-6 pb-10">
    @if($jadwalAktif)
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] text-primary font-bold uppercase tracking-wider">Perjalanan Aktif</p>
                <h3 class="text-sm font-bold text-gray-800 mt-1">{{ $jadwalAktif->rute->kota_asal }} - {{ $jadwalAktif->rute->kota_tujuan }}</h3>
                <p class="text-xs text-gray-500">{{ $jadwalAktif->armada->nama_bus }} ({{ $jadwalAktif->armada->plat_nomor }})</p>
            </div>
            <i class="fa-solid fa-location-arrow text-primary text-xl opacity-40"></i>
        </div>

        <form id="form-update-status" onsubmit="submitStatus(event)" class="space-y-6">
            <input type="hidden" name="jadwal_id" value="{{ $jadwalAktif->id }}">

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Lokasi Terkini</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-primary">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </span>
                    <input type="text" name="lokasi_sekarang" required class="input-modern w-full pl-11 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-sm shadow-sm" placeholder="Contoh: Loket Pusat, Pool, Rest Area...">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-3">Kondisi / Status</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-100 bg-white cursor-pointer shadow-sm transition active:scale-95 has-[:checked]:bg-primary has-[:checked]:text-white has-[:checked]:border-primary group">
                        <input type="radio" name="status" value="Di Perjalanan" required class="hidden" checked>
                        <div class="text-center">
                            <i class="fa-solid fa-bus-simple block mb-1"></i>
                            <span class="text-[10px] font-bold">Lancar</span>
                        </div>
                    </label>
                    <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-100 bg-white cursor-pointer shadow-sm transition active:scale-95 has-[:checked]:bg-blue-500 has-[:checked]:text-white has-[:checked]:border-blue-500 group">
                        <input type="radio" name="status" value="Istirahat" class="hidden">
                        <div class="text-center">
                            <i class="fa-solid fa-mug-hot block mb-1"></i>
                            <span class="text-[10px] font-bold">Istirahat</span>
                        </div>
                    </label>
                    <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-100 bg-white cursor-pointer shadow-sm transition active:scale-95 has-[:checked]:bg-danger has-[:checked]:text-white has-[:checked]:border-danger group">
                        <input type="radio" name="status" value="Kendala" class="hidden">
                        <div class="text-center">
                            <i class="fa-solid fa-triangle-exclamation block mb-1"></i>
                            <span class="text-[10px] font-bold">Kendala</span>
                        </div>
                    </label>
                    <label class="relative flex items-center justify-center p-3 rounded-xl border border-gray-100 bg-white cursor-pointer shadow-sm transition active:scale-95 has-[:checked]:bg-success has-[:checked]:text-white has-[:checked]:border-success group">
                        <input type="radio" name="status" value="Tiba" class="hidden">
                        <div class="text-center">
                            <i class="fa-solid fa-flag-checkered block mb-1"></i>
                            <span class="text-[10px] font-bold">Tiba</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="input-modern w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-sm shadow-sm" placeholder="Misal: Macet di Flyover, sedang ganti ban, dll..."></textarea>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary/30 transition active:scale-95 flex items-center justify-center gap-3">
                <i class="fa-solid fa-paper-plane"></i> Kirim Update Posisi
            </button>
        </form>

        <div class="mt-10">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Laporan Sebelumnya</h3>
            <div class="space-y-4 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100">
                @forelse($riwayatUpdate as $up)
                    <div class="relative pl-8">
                        <div class="absolute left-1.5 top-1 w-3 h-3 bg-white border-2 border-primary rounded-full z-10"></div>
                        <div class="bg-white rounded-xl p-3 border border-gray-50 shadow-sm">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-bold text-primary">{{ $up->status }}</span>
                                <span class="text-[10px] text-gray-400">{{ $up->created_at->format('H:i') }} WIB</span>
                            </div>
                            <p class="text-xs font-bold text-gray-800 mt-1">{{ $up->lokasi_sekarang }}</p>
                            @if($up->keterangan)
                                <p class="text-[10px] text-gray-500 italic mt-1 leading-relaxed">"{{ $up->keterangan }}"</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-secondary italic pl-8">Belum ada laporan posisi.</p>
                @endforelse
            </div>
        </div>

    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-location-dot text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Tidak Ada Perjalanan</h3>
            <p class="text-sm text-secondary px-4 leading-relaxed">Anda tidak memiliki jadwal perjalanan aktif saat ini untuk dilaporkan statusnya.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function submitStatus(e) {
        e.preventDefault();
        
        let formData = $('#form-update-status').serialize();

        Swal.fire({
            title: 'Kirim Laporan?',
            text: "Pastikan lokasi yang Anda masukkan sudah sesuai.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('driver.status.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload halaman agar riwayat timeline terupdate
                                window.location.reload();
                            });
                        }
                    },
                    error: function(jqxhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim data. Coba lagi.'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush