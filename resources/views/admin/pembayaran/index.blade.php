@extends('layouts.app')

@section('title', 'Transaksi Pembayaran')
@section('page_title', 'Data Pembayaran Tiket')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari Kode Tiket atau Nama...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-file-invoice-dollar"></i> Input Pembayaran
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Kode Tiket & Penumpang</th>
                    <th class="px-6 py-4">Nominal & Metode</th>
                    <th class="px-6 py-4">Bukti Transfer</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50"></div>
</div>

<div id="pembayaran-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 rounded-t-2xl z-10">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Input Pembayaran Tiket</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="pembayaran-form" onsubmit="saveForm(event)" enctype="multipart/form-data">
            <input type="hidden" id="pembayaran-id" name="id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Tiket (Unpaid/Pending)</label>
                    <select id="tiket_id" name="tiket_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <option value="">-- Cari Kode Tiket --</option>
                        @foreach($tiketPending as $item)
                            <option value="{{ $item->id }}">{{ $item->kode_tiket }} - {{ $item->penumpang->nama }} (Rp {{ number_format($item->harga,0,',','.') }})</option>
                        @endforeach
                    </select>
                    <p id="edit-tiket-hint" class="text-[10px] text-gray-500 mt-1 hidden">Tiket yang sedang diedit sudah dipilih.</p>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-tiket_id"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Jumlah Bayar (Rp)</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-jumlah_bayar"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Pembayaran</label>
                        <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Pending">Pending (Menunggu Cek)</option>
                            <option value="Lunas">Lunas (Disetujui)</option>
                            <option value="Ditolak">Ditolak (Tidak Valid)</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                            <option value="E-Wallet DANA">E-Wallet DANA</option>
                            <option value="E-Wallet GoPay">E-Wallet GoPay</option>
                            <option value="Tunai / Cash">Tunai (Di Loket)</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-metode_pembayaran"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Upload Bukti Transfer (Opsional)</label>
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" class="input-modern w-full px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-bukti_transfer"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 rounded-b-2xl z-10">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';

    $(document).ready(function() {
        fetchPembayaran(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchPembayaran(1, currentSearch), 400);
        });
    });

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
    
    function formatWaktu(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function fetchPembayaran(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.pembayaran.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-receipt text-2xl block mb-2 opacity-50"></i> Belum ada data pembayaran</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeStatus = '';
                        if(item.status === 'Lunas') badgeStatus = 'bg-success/10 text-success border-success/20';
                        else if(item.status === 'Pending') badgeStatus = 'bg-warning/10 text-warning border-warning/20';
                        else badgeStatus = 'bg-danger/10 text-danger border-danger/20';
                        
                        let buktiLink = item.bukti_transfer 
                            ? `<a href="/storage/${item.bukti_transfer}" target="_blank" class="text-xs text-primary hover:underline bg-primary/5 px-2 py-1 rounded-md"><i class="fa-solid fa-image mr-1"></i> Lihat Bukti</a>` 
                            : `<span class="text-xs text-gray-400 italic">Tidak ada foto</span>`;

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-mono text-xs font-bold text-gray-800">${item.tiket.kode_tiket}</p>
                                    <p class="font-semibold text-primary text-sm mt-1">${item.tiket.penumpang.nama}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">${formatRupiah(item.jumlah_bayar)}</p>
                                    <p class="text-xs text-secondary mt-1"><i class="fa-solid fa-building-columns mr-1 w-3"></i> ${item.metode_pembayaran}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">${formatWaktu(item.tanggal_bayar)}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    ${buktiLink}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border ${badgeStatus}">${item.status}</span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deletePembayaran(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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

    function renderPagination(meta) {
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> data</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchPembayaran(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchPembayaran(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchPembayaran(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#pembayaran-form')[0].reset();
        $('#pembayaran-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Input Pembayaran Baru');
        
        // Pastikan opsi disabled dihapus jika ada
        $('#tiket_id').find('option.edit-only').remove();
        $('#edit-tiket-hint').addClass('hidden');

        $('#pembayaran-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/pembayaran/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#pembayaran-id').val(data.id);
                
                // Jika tiket_id tidak ada di dropdown (karena sudah lunas & tidak dipanggil di index()), tambahkan opsinya
                if ($('#tiket_id option[value="' + data.tiket_id + '"]').length === 0) {
                    $('#tiket_id').append(`<option value="${data.tiket_id}" class="edit-only">${data.tiket.kode_tiket}</option>`);
                }
                
                $('#tiket_id').val(data.tiket_id);
                $('#edit-tiket-hint').removeClass('hidden');

                $('#metode_pembayaran').val(data.metode_pembayaran);
                $('#jumlah_bayar').val(data.jumlah_bayar);
                $('#status').val(data.status);
                
                $('#modal-title').text('Edit Pembayaran / Verifikasi');
                
                $('#pembayaran-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#pembayaran-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#pembayaran-id').val();
        let url = id ? `/admin/pembayaran/${id}` : "{{ route('admin.pembayaran.store') }}";
        
        // Gunakan FormData untuk file upload
        let formData = new FormData($('#pembayaran-form')[0]);
        if(id) { formData.append('_method', 'PUT'); } // Trick Laravel via POST

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchPembayaran(currentPage, currentSearch);
                }
            },
            error: function(jqxhr) {
                if (jqxhr.status === 422) {
                    let errors = jqxhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        $(`#err-${key}`).removeClass('hidden').text(val[0]);
                    });
                }
            }
        });
    }

    function deletePembayaran(id) {
        Swal.fire({
            title: 'Hapus Data Pembayaran?',
            text: "Status tiket mungkin perlu Anda sesuaikan kembali secara manual!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/pembayaran/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchPembayaran(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush