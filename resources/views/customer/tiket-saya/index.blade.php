@extends('layouts.app-customer')

@section('title', 'Tiket Saya')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-12 pb-10 px-6 relative text-center shrink-0 shadow-md">
    <h1 class="text-white text-base font-bold tracking-wide">Tiket Saya</h1>
</div>

<div class="px-4 mt-4 shrink-0">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1 flex justify-between gap-1 text-center overflow-x-auto app-container">
        <button onclick="switchTab(this, 'Semua')" class="tab-btn flex-1 py-2 px-3 text-[11px] font-bold rounded-lg transition-all bg-primary text-white shadow-sm whitespace-nowrap">
            Semua
        </button>
        <button onclick="switchTab(this, 'Aktif')" class="tab-btn flex-1 py-2 px-3 text-[11px] font-bold rounded-lg transition-all text-secondary hover:bg-gray-50 whitespace-nowrap">
            Aktif
        </button>
        <button onclick="switchTab(this, 'Selesai')" class="tab-btn flex-1 py-2 px-3 text-[11px] font-bold rounded-lg transition-all text-secondary hover:bg-gray-50 whitespace-nowrap">
            Selesai
        </button>
        <button onclick="switchTab(this, 'Dibatalkan')" class="tab-btn flex-1 py-2 px-3 text-[11px] font-bold rounded-lg transition-all text-secondary hover:bg-gray-50 whitespace-nowrap">
            Dibatalkan
        </button>
    </div>
</div>

<div class="px-6 mt-5 space-y-4 pb-24" id="ticket-list-container">
    </div>

<div id="detail-ticket-modal" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/40 backdrop-blur-sm p-0 animate-fade-in">
    <div class="bg-[#f8fafc] w-full max-w-md rounded-t-[2.5rem] shadow-2xl border-t border-gray-100 transform transition-all translate-y-full duration-300 flex flex-col max-h-[92%]" id="modal-card">
        
        <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between shrink-0 rounded-t-[2.5rem]">
            <h3 class="font-bold text-gray-800 text-sm"><i class="fa-solid fa-circle-info text-primary mr-1.5"></i> Detail Tiket</h3>
            <button onclick="closeTicketModal()" class="text-gray-400 p-1 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div class="overflow-y-auto p-6 space-y-5 flex-1 app-container pb-12">
            
            <div class="bg-primary rounded-2xl p-4 text-white shadow-sm flex justify-between items-center relative overflow-hidden">
                <div>
                    <span id="md-bus-name" class="text-[10px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded">AKDP-01</span>
                    <div class="flex items-center gap-2 mt-2 font-bold text-base">
                        <span id="md-asal">Medan</span>
                        <i class="fa-solid fa-right-long text-xs opacity-60"></i>
                        <span id="md-tujuan">Binjai</span>
                    </div>
                </div>
                <span class="bg-success text-white text-[9px] font-bold px-2 py-0.5 rounded-md" id="md-status-tiket">Aktif</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Jam Keberangkatan</p>
                    <p id="md-jam" class="text-sm font-bold text-gray-800 mt-0.5">08:00 WIB</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Tanggal</p>
                    <p id="md-tanggal" class="text-xs font-bold text-gray-700 mt-0.5">Rabu, 20 Mei 2026</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Nomor Kursi</p>
                    <p id="md-kursi" class="text-base font-bold text-primary mt-0.5">A1</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Total Pembayaran</p>
                    <p id="md-harga" class="text-sm font-bold text-gray-800 mt-0.5">Rp 65.000</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-2">Data Penumpang</p>
                <h4 id="md-nama-penumpang" class="text-xs font-bold text-gray-800">Budi Santoso</h4>
                <p id="md-nik-penumpang" class="text-[10px] text-secondary font-mono mt-0.5">NIK: 1234567890123456</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3">
                    <img id="md-qrcode-img" src="" alt="QR Code E-Tiket" class="w-36 h-36">
                </div>
                <p id="md-kode-tiket-text" class="font-mono text-xs font-bold text-gray-800 tracking-wider">AKDP2505200001</p>
                <p class="text-[9px] text-gray-400 mt-2 max-w-[200px]">Tunjukkan QR Code ini kepada petugas atau supir saat naik bus.</p>
            </div>

        </div>

       <div class="p-4 px-6 bg-white border-t border-gray-50 flex gap-3 shrink-0 pb-6 rounded-b-3xl">
            <a href="#" target="_blank" id="btn-download-tiket" class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2 text-center">
                <i class="fa-solid fa-download"></i> Download
            </a>
            <button onclick="shareTicket()" class="flex-1 bg-primary hover:bg-blue-900 text-white font-bold py-3 rounded-xl text-xs transition active:scale-95 flex items-center justify-center gap-2 shadow-md shadow-primary/20">
                <i class="fa-solid fa-share-nodes"></i> Bagikan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStatusTab = 'Semua';

    $(document).ready(function() {
        // Ambil data pertama kali halaman dimuat
        fetchCustomerTickets(currentStatusTab);
    });

    // Fungsi ganti tab filter kategori tiket
    function switchTab(buttonElement, statusName) {
        // Atur ulang style tombol pasif
        $('.tab-btn').removeClass('bg-primary text-white shadow-sm').addClass('text-secondary hover:bg-gray-50');
        
        // Atur style tombol aktif yang baru diklik
        $(buttonElement).removeClass('text-secondary hover:bg-gray-50').addClass('bg-primary text-white shadow-sm');
        
        currentStatusTab = statusName;
        fetchCustomerTickets(statusName);
    }

    // Ambil data dari server
    function fetchCustomerTickets(status) {
        $.ajax({
            url: "{{ route('customer.tiket-saya.data') }}",
            type: "GET",
            data: { status: status },
            success: function(response) {
                $('#ticket-list-container').html(response.html);
            }
        });
    }

    // Buka Modal Pop Up Detail E-Tiket (Slide Up Gaya Mobile)
    function viewTicketDetail(tiketId) {
        $.ajax({
            url: `/customer/tiket-saya/${tiketId}/detail`,
            type: "GET",
            success: function(data) {


            $('#btn-download-tiket').attr('href', `/customer/tiket-saya/${tiketId}/cetak`);
                // Pasang data ke komponen HTML Modal
                $('#md-bus-name').text(data.jadwal.armada.nama_bus);
                $('#md-asal').text(data.jadwal.rute.kota_asal);
                $('#md-tujuan').text(data.jadwal.rute.kota_tujuan);
                $('#md-jam').text(data.jam_indo);
                $('#md-tanggal').text(data.tanggal_indo);
                $('#md-kursi').text(data.kursi.nomor_kursi);
                $('#md-harga').text(data.harga_indo);
                $('#md-nama-penumpang').text(data.penumpang.nama);
                $('#md-nik-penumpang').text('NIK: ' + data.penumpang.nik);
                $('#md-kode-tiket-text').text(data.kode_tiket);
                
                // Set text status tiket
                let statusTxt = data.status_tiket === 'Digunakan' ? 'Selesai' : data.status_tiket;
                $('#md-status-tiket').text(statusTxt);

                // Buat QR Code instan dari API QRServer berdasarkan kode tiket unik
                $('#md-qrcode-img').attr('src', `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${data.kode_tiket}`);

                // Animasi Slide Up Muncul
                $('#detail-ticket-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { 
                    $('#modal-card').removeClass('translate-y-full').addClass('translate-y-0'); 
                }, 50);
            }
        });
    }

    function closeTicketModal() {
        $('#modal-card').removeClass('translate-y-0').addClass('translate-y-full');
        setTimeout(() => { 
            $('#detail-ticket-modal').removeClass('flex').addClass('hidden'); 
        }, 200);
    }

    function shareTicket() {
        // Simulasi fitur share bawaan hp / toast sukses
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Tautan tiket disalin ke papan klip!',
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>
@endpush