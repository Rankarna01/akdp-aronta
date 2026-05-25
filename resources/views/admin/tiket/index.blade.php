@extends('layouts.app')

@section('title', 'Pemesanan Tiket')
@section('page_title', 'Transaksi Tiket Penumpang')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari Kode Tiket / Nama...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-ticket"></i> Pesan Tiket Baru
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Kode & Penumpang</th>
                    <th class="px-6 py-4">Jadwal & Rute</th>
                    <th class="px-6 py-4">Kursi & Harga</th>
                    <th class="px-6 py-4 text-center">Status Pembayaran</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50"></div>
</div>

<div id="tiket-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 rounded-t-2xl z-10">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Form Pemesanan Tiket</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="tiket-form" onsubmit="saveForm(event)">
            <input type="hidden" id="tiket-id" name="id">
            
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Penumpang</label>
                        <select id="penumpang_id" name="penumpang_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Cari Penumpang --</option>
                            @foreach($penumpang as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->nik }})</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-penumpang_id"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Jadwal</label>
                        <select id="jadwal_id" name="jadwal_id" onchange="loadKursiTersedia()" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Pilih Jadwal & Rute --</option>
                            @foreach($jadwal as $item)
                                <option value="{{ $item->id }}">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }} | {{ $item->rute->kota_asal }}-{{ $item->rute->kota_tujuan }} ({{ $item->armada->nama_bus }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-jadwal_id"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Kursi (Otomatis)</label>
                        <select id="kursi_id" name="kursi_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" disabled>
                            <option value="">Pilih jadwal terlebih dahulu</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-kursi_id"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Harga Tiket (Rp)</label>
                        <input type="number" id="harga" name="harga" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Terisi otomatis">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-harga"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Pembayaran</label>
                        <select id="status_pembayaran" name="status_pembayaran" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Unpaid">Belum Dibayar (Unpaid)</option>
                            <option value="Pending">Menunggu Verifikasi (Pending)</option>
                            <option value="Paid">Lunas (Paid)</option>
                            <option value="Failed">Gagal / Dibatalkan (Failed)</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status_pembayaran"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Tiket</label>
                        <select id="status_tiket" name="status_tiket" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Aktif">Aktif (Dapat Digunakan)</option>
                            <option value="Digunakan">Sudah Digunakan</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status_tiket"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 rounded-b-2xl z-10">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Proses Tiket</button>
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
        fetchTiket(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchTiket(1, currentSearch), 400);
        });
    });

    // Helper formatter Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
    
    function formatTanggal(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // FUNGSI PENTING: Load Kursi Kosong secara Otomatis via AJAX
    function loadKursiTersedia(selectedKursiId = null) {
        let jadwalId = $('#jadwal_id').val();
        let tiketId = $('#tiket-id').val(); // Bawa ID tiket jika sedang mode Edit
        let kursiSelect = $('#kursi_id');
        let hargaInput = $('#harga');

        if (!jadwalId) {
            kursiSelect.html('<option value="">Pilih jadwal terlebih dahulu</option>').prop('disabled', true);
            hargaInput.val('');
            return;
        }

        kursiSelect.html('<option value="">Loading kursi...</option>').prop('disabled', true);

        // Fetch data kursi kosong dari controller
        $.ajax({
            url: `/admin/tiket/get-kursi/${jadwalId}`,
            type: "GET",
            data: { tiket_id: tiketId }, // Kirim query params untuk pengecualian saat Edit
            success: function(response) {
                hargaInput.val(response.harga);
                
                let options = '<option value="">-- Pilih Kursi --</option>';
                if (response.kursi.length === 0) {
                    options = '<option value="">Kursi Penuh / Habis!</option>';
                } else {
                    response.kursi.forEach(function(k) {
                        let isSelected = (selectedKursiId == k.id) ? 'selected' : '';
                        options += `<option value="${k.id}" ${isSelected}>Kursi: ${k.nomor_kursi}</option>`;
                    });
                }
                
                kursiSelect.html(options).prop('disabled', false);
            }
        });
    }

    function fetchTiket(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.tiket.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-ticket-simple text-2xl block mb-2 opacity-50"></i> Belum ada transaksi tiket</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeBayar = '';
                        if(item.status_pembayaran === 'Paid') badgeBayar = 'bg-success/10 text-success border-success/20';
                        else if(item.status_pembayaran === 'Pending') badgeBayar = 'bg-warning/10 text-warning border-warning/20';
                        else if(item.status_pembayaran === 'Unpaid') badgeBayar = 'bg-gray-100 text-gray-600 border-gray-200';
                        else badgeBayar = 'bg-danger/10 text-danger border-danger/20';
                        
                        let statusTiketHtml = item.status_tiket === 'Dibatalkan' ? `<span class="text-[10px] bg-danger text-white px-1.5 py-0.5 rounded ml-1">Batal</span>` : '';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-mono text-xs font-bold text-primary bg-primary/5 inline-block px-2 py-1 rounded mb-1 border border-primary/10">${item.kode_tiket}</p>
                                    <p class="font-semibold text-gray-800 text-sm mt-1">${item.penumpang.nama}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">${item.jadwal.rute.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-gray-400 text-[10px]"></i> ${item.jadwal.rute.kota_tujuan}</p>
                                    <p class="text-xs text-secondary mt-1"><i class="fa-regular fa-calendar mr-1"></i> ${formatTanggal(item.jadwal.tanggal)} ${item.jadwal.waktu_berangkat.substring(0,5)}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 bg-gray-100 inline-block px-2 py-0.5 rounded border border-gray-200"><i class="fa-solid fa-chair mr-1 text-gray-400"></i> ${item.kursi.nomor_kursi}</p>
                                    <p class="text-xs font-semibold text-success mt-1">${formatRupiah(item.harga)}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border ${badgeBayar}">${item.status_pembayaran}</span>
                                    ${statusTiketHtml}
                                </td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteTiket(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> tiket</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchTiket(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchTiket(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchTiket(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#tiket-form')[0].reset();
        $('#tiket-id').val('');
        $('#kursi_id').html('<option value="">Pilih jadwal terlebih dahulu</option>').prop('disabled', true);
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Pesan Tiket Baru');
        
        $('#tiket-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/tiket/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#tiket-id').val(data.id);
                $('#penumpang_id').val(data.penumpang_id);
                $('#jadwal_id').val(data.jadwal_id);
                
                // Trigger load kursi & set value kursi yang dipilih sebelumnya
                loadKursiTersedia(data.kursi_id);
                
                // Set timeout sejenak agar harga tidak ketimpa reset dari loadKursiTersedia
                setTimeout(() => {
                    $('#harga').val(data.harga);
                }, 300);

                $('#status_pembayaran').val(data.status_pembayaran);
                $('#status_tiket').val(data.status_tiket);
                
                $('#modal-title').text('Edit Data Pemesanan Tiket');
                
                $('#tiket-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#tiket-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#tiket-id').val();
        let url = id ? `/admin/tiket/${id}` : "{{ route('admin.tiket.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#tiket-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchTiket(currentPage, currentSearch);
                }
            },
            error: function(jqxhr) {
                // Tangkap validasi unik kursi dari controller (422)
                if (jqxhr.status === 422) {
                    if(jqxhr.responseJSON.message === 'Gagal! Kursi tersebut sudah dipesan.') {
                         Swal.fire({ icon: 'error', title: 'Oops...', text: jqxhr.responseJSON.message });
                    } else {
                        let errors = jqxhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            $(`#err-${key}`).removeClass('hidden').text(val[0]);
                        });
                    }
                }
            }
        });
    }

    function deleteTiket(id) {
        Swal.fire({
            title: 'Batalkan & Hapus Tiket?',
            text: "Tiket akan dihapus dan kursi akan kembali kosong!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/tiket/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchTiket(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush