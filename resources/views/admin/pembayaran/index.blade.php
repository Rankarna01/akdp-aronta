@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Validasi Transaksi Tiket')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    
    <div class="flex bg-gray-50 p-1 rounded-xl border border-gray-200 w-full sm:w-auto overflow-x-auto custom-scrollbar shrink-0">
        <button onclick="filterStatus('Semua')" id="tab-Semua" class="tab-btn px-4 py-2 text-xs font-bold rounded-lg transition-all bg-white text-primary shadow-sm whitespace-nowrap">Semua</button>
        <button onclick="filterStatus('Pending')" id="tab-Pending" class="tab-btn px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all whitespace-nowrap">
            Menunggu <span class="bg-warning text-white text-[9px] px-1.5 py-0.5 rounded-full ml-1">!</span>
        </button>
        <button onclick="filterStatus('Lunas')" id="tab-Lunas" class="tab-btn px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all whitespace-nowrap">Lunas</button>
        <button onclick="filterStatus('Ditolak')" id="tab-Ditolak" class="tab-btn px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 rounded-lg transition-all whitespace-nowrap">Ditolak</button>
    </div>

    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari Kode Tiket / Nama...">
    </div>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden w-full">
    <div class="overflow-x-auto w-full custom-scrollbar">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Kode Tiket / Rute</th>
                    <th class="px-6 py-4">Penumpang</th>
                    <th class="px-6 py-4">Nominal / Metode</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50"></div>
</div>

<div id="image-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-lg max-h-[95vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-95 duration-300 relative" id="image-card">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 w-8 h-8 bg-black/50 text-white rounded-full hover:bg-black transition flex items-center justify-center z-10 shrink-0">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="p-6 text-center border-b border-gray-100 bg-gray-50 shrink-0">
            <h3 class="font-bold text-gray-800 text-base">Bukti Transfer</h3>
            <p id="modal-kode-tiket" class="text-xs font-mono font-bold text-primary mt-1"></p>
        </div>
        
        <div class="bg-gray-200 w-full flex-1 flex items-center justify-center overflow-auto p-4 custom-scrollbar">
            <img id="modal-img-preview" src="" alt="Bukti Pembayaran" class="max-w-full h-auto shadow-sm rounded border border-gray-300">
        </div>

        <div class="p-4 bg-white flex items-center justify-between">
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Total Tagihan</p>
                <p id="modal-nominal" class="text-lg font-bold text-primary"></p>
            </div>
            <div class="flex gap-2" id="modal-action-buttons">
                </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';
    let currentStatus = 'Semua';

    $(document).ready(function() {
        fetchPembayaran(currentPage, currentSearch, currentStatus);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchPembayaran(1, currentSearch, currentStatus), 400);
        });
    });

    // Fitur Tab Filter Status
    function filterStatus(status) {
        currentStatus = status;
        
        // Reset style semua tab
        $('.tab-btn').removeClass('bg-white text-primary shadow-sm').addClass('text-gray-500 hover:text-gray-700');
        // Aktifkan tab yang diklik
        $(`#tab-${status}`).removeClass('text-gray-500 hover:text-gray-700').addClass('bg-white text-primary shadow-sm');
        
        fetchPembayaran(1, currentSearch, currentStatus);
    }

    // Ambil Data Pembayaran
    function fetchPembayaran(page, search, status) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.pembayaran.data') }}",
            type: "GET",
            data: { page: page, search: search, status: status },
            success: function(response) {
                let htmlRows = '';
                
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-10 text-secondary"><i class="fa-solid fa-receipt text-4xl block mb-2 opacity-30"></i> Tidak ada transaksi yang sesuai.</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        // Badge Status Pembayaran
                        let badgeStatus = '';
                        if (item.status === 'Pending') badgeStatus = '<span class="bg-warning/10 text-warning border border-warning/20 px-2.5 py-1 rounded-full text-[10px] font-bold"><i class="fa-solid fa-clock mr-1"></i> Menunggu Verifikasi</span>';
                        else if (item.status === 'Lunas') badgeStatus = '<span class="bg-success/10 text-success border border-success/20 px-2.5 py-1 rounded-full text-[10px] font-bold"><i class="fa-solid fa-check-double mr-1"></i> Lunas</span>';
                        else badgeStatus = '<span class="bg-danger/10 text-danger border border-danger/20 px-2.5 py-1 rounded-full text-[10px] font-bold"><i class="fa-solid fa-xmark mr-1"></i> Ditolak</span>';

                        // Tombol Aksi
                        let actionButtons = '';
                        let imagePath = `/storage/${item.bukti_transfer}`; // Path gambar Laravel storage
                        
                        if (item.status === 'Pending') {
                            actionButtons = `
                                <div class="flex justify-center gap-2">
                                    <button onclick="previewImage('${imagePath}', '${item.tiket.kode_tiket}', ${item.jumlah_bayar}, ${item.id})" class="bg-blue-50 hover:bg-blue-100 text-primary px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 border border-blue-200">
                                        <i class="fa-regular fa-image"></i> Cek Struk
                                    </button>
                                    <button onclick="prosesVerifikasi(${item.id}, 'approve')" class="bg-success hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button onclick="prosesVerifikasi(${item.id}, 'reject')" class="bg-rose-50 hover:bg-rose-100 text-danger border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            `;
                        } else {
                            // Jika sudah lunas/gagal, cuma bisa lihat struk
                            actionButtons = `
                                <div class="flex justify-center">
                                    <button onclick="previewImage('${imagePath}', '${item.tiket.kode_tiket}', ${item.jumlah_bayar}, null)" class="text-gray-400 hover:text-primary transition flex items-center gap-1 text-xs font-bold bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                                        <i class="fa-regular fa-image"></i> Lihat Bukti
                                    </button>
                                </div>
                            `;
                        }

                        // Format Harga Rupiah
                        let hargaFormat = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(item.jumlah_bayar);

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-primary bg-blue-50 border border-blue-100 px-2 py-0.5 rounded block w-max mb-1">${item.tiket.kode_tiket}</span>
                                    <span class="text-[10px] font-bold text-gray-500">${item.tiket.jadwal.rute.kota_asal} &rarr; ${item.tiket.jadwal.rute.kota_tujuan}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800 text-sm">
                                    ${item.tiket.penumpang.nama}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">${hargaFormat}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">${item.metode_pembayaran}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    ${badgeStatus}
                                </td>
                                <td class="px-6 py-4">
                                    ${actionButtons}
                                </td>
                            </tr>
                        `;
                    });
                }
                
                $('#table-body').html(htmlRows);
                renderPagination(response);
            }
        });
    }

    // Render Paginasi
    function renderPagination(meta) { /* Logika paginasi persis sama seperti module sebelumnya */
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> data</p>`;
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchPembayaran(${meta.current_page - 1}, currentSearch, currentStatus)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) { paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`; } 
                else { paginationHtml += `<button onclick="fetchPembayaran(${i}, currentSearch, currentStatus)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`; }
            }
            paginationHtml += `<button onclick="fetchPembayaran(${meta.current_page + 1}, currentSearch, currentStatus)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    // Modal Image Preview
    function previewImage(imageSrc, kodeTiket, nominal, pembayaranId) {
        let hargaFormat = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(nominal);
        
        $('#modal-img-preview').attr('src', imageSrc);
        $('#modal-kode-tiket').text(kodeTiket);
        $('#modal-nominal').text(hargaFormat);

        // Jika ID dikirim (Berarti status Pending), tampilkan tombol Aksi di dalam Modal
        if (pembayaranId !== null) {
            $('#modal-action-buttons').html(`
                <button onclick="prosesVerifikasi(${pembayaranId}, 'reject')" class="bg-rose-50 hover:bg-rose-100 text-danger font-bold py-2 px-4 rounded-xl text-xs transition">Tolak</button>
                <button onclick="prosesVerifikasi(${pembayaranId}, 'approve')" class="bg-success hover:bg-green-600 text-white font-bold py-2 px-6 rounded-xl text-xs transition shadow-md shadow-success/30">Terima Lunas</button>
            `);
        } else {
            $('#modal-action-buttons').html('');
        }
        
        $('#image-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#image-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function closeImageModal() {
        $('#image-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#image-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    // Eksekusi Update Status (Approve / Reject) via AJAX
    function prosesVerifikasi(id, action) {
        let textConfirm = action === 'approve' ? "Verifikasi pembayaran ini sebagai LUNAS?" : "Tolak bukti pembayaran ini?";
        let btnColor = action === 'approve' ? '#10b981' : '#f43f5e';
        let btnText = action === 'approve' ? 'Ya, Lunas!' : 'Ya, Tolak!';

        Swal.fire({
            title: 'Konfirmasi Aksi',
            text: textConfirm,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: btnColor,
            cancelButtonColor: '#94a3b8',
            confirmButtonText: btnText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Sembunyikan modal gambar jika sedang terbuka
                closeImageModal(); 

                $.ajax({
                    url: `/admin/pembayaran/${id}`,
                    type: "POST",
                    data: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}',
                        action: action
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                            // Refresh tabel
                            fetchPembayaran(currentPage, currentSearch, currentStatus);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush