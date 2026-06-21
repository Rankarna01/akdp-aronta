@extends('layouts.app-customer')

@section('title', 'Pembayaran')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-10 pb-8 px-6 text-white shrink-0 relative shadow-md z-20">
    <div class="flex items-center gap-4 mb-4">
        <a href="javascript:history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold">Pembayaran</h1>
    </div>
</div>

<div class="px-6 mt-6 pb-32">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 flex justify-between items-center">
        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Tagihan</span>
        <span class="text-lg font-bold text-primary">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</span>
    </div>

    <form id="form-pembayaran" onsubmit="submitPembayaran(event)" enctype="multipart/form-data">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Metode Pembayaran</h3>
            
            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Bank / E-Wallet</label>
                <div class="relative border border-gray-200 rounded-xl p-3 flex items-start gap-3 bg-gray-50">
                    <i class="fa-solid fa-building-columns text-primary mt-0.5"></i>
                    <div>
                        <select name="metode_pembayaran" required class="w-full text-xs font-bold text-gray-800 bg-transparent border-none p-0 focus:ring-0 appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Bank / E-Wallet</option>
                            @foreach($metode_pembayaran as $metode)
                                <option value="{{ $metode->nama_bank }}">{{ $metode->nama_bank }} - {{ $metode->nomor_rekening }} (a.n {{ $metode->atas_nama }})</option>
                            @endforeach
                        </select>
                        <p class="text-[9px] text-secondary mt-1">Silakan transfer sesuai nominal ke rekening di atas.</p>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Upload Bukti Transfer</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition relative">
                    <input type="file" name="bukti_transfer" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="file-upload">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-primary mb-2"></i>
                    <p class="text-xs font-bold text-gray-800" id="file-name">Klik untuk upload bukti transfer</p>
                    <p class="text-[9px] text-gray-400 mt-1">JPG, PNG maks. 5MB</p>
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan (Opsional)</label>
                <input type="text" name="keterangan" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs" placeholder="Contoh: Transfer dari m-banking Budi">
            </div>
        </div>
    </form>

</div>

<div class="fixed bottom-0 left-0 right-0 w-full max-w-md mx-auto bg-white border-t border-gray-100 p-4 px-6 z-50 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] pb-[calc(1rem+env(safe-area-inset-bottom))]">
    <button type="submit" form="form-pembayaran" class="w-full bg-primary hover:bg-blue-900 text-white text-sm font-bold py-3.5 rounded-xl transition active:scale-95 shadow-lg shadow-primary/30">
        Konfirmasi Pembayaran
    </button>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('nav').hide(); 

        // Efek ubah nama file saat diupload
        $('#file-upload').change(function(e) {
            let fileName = e.target.files[0].name;
            $('#file-name').text(fileName).addClass('text-success');
        });
    });

    function submitPembayaran(e) {
        e.preventDefault();
        
        let formData = new FormData($('#form-pembayaran')[0]);

        $.ajax({
            url: "{{ route('customer.checkout.proses-pembayaran', $tiket->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Nanti akan kita arahkan ke halaman 'Tiket Saya' (Screen 9)
                        // Untuk sementara kita arahkan ke home dulu sampai modul Tiket Saya selesai dibuat
                        window.location.href = "{{ route('customer.home') }}";
                    });
                }
            },
            error: function(jqxhr) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan. Pastikan ukuran file gambar tidak lebih dari 5MB.' });
            }
        });
    }
</script>
@endpush