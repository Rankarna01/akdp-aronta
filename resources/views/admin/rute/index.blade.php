@extends('layouts.app')

@section('title', 'Manajemen Rute')
@section('page_title', 'Data Rute Perjalanan')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari kota asal atau tujuan...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Rute
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Rute Perjalanan (Asal - Tujuan)</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Harga Dasar</th>
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

<div id="rute-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Data Rute</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="rute-form" onsubmit="saveForm(event)">
            <input type="hidden" id="rute-id" name="id">
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Kota Asal</label>
                        <input type="text" id="kota_asal" name="kota_asal" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Medan">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-kota_asal"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Kota Tujuan</label>
                        <input type="text" id="kota_tujuan" name="kota_tujuan" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Banda Aceh">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-kota_tujuan"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipe Kelas Bus</label>
                        <select id="tipe_bus" name="tipe_bus" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Economy">Economy</option>
                            <option value="Executive">Executive</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-tipe_bus"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Harga Dasar (Rp)</label>
                        <input type="number" id="harga_dasar" name="harga_dasar" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: 250000">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-harga_dasar"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status</label>
                        <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Simpan Rute</button>
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
        fetchRute(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchRute(1, currentSearch), 400);
        });
    });

    // Helper formatter Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function fetchRute(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.rute.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-folder-open text-2xl block mb-2 opacity-50"></i> Tidak ada data rute ditemukan</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = item.status === 'Aktif' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger';
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-primary/10 text-primary w-8 h-8 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-map-pin"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">${item.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-gray-400 text-xs"></i> ${item.kota_tujuan}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-600">
                                    ${item.tipe_bus}
                                </td>
                                <td class="px-6 py-4 font-semibold text-primary">
                                    ${formatRupiah(item.harga_dasar)}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold ${badgeColor}">${item.status}</span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteRute(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> rute</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchRute(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchRute(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchRute(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#rute-form')[0].reset();
        $('#rute-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Data Rute Baru');
        
        $('#rute-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/rute/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#rute-id').val(data.id);
                $('#kota_asal').val(data.kota_asal);
                $('#kota_tujuan').val(data.kota_tujuan);
                $('#tipe_bus').val(data.tipe_bus);
                $('#harga_dasar').val(data.harga_dasar);
                $('#status').val(data.status);
                
                $('#modal-title').text('Edit Data Rute');
                
                $('#rute-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#rute-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#rute-id').val();
        let url = id ? `/admin/rute/${id}` : "{{ route('admin.rute.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#rute-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchRute(currentPage, currentSearch);
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

    function deleteRute(id) {
        Swal.fire({
            title: 'Hapus Data Rute?',
            text: "Rute ini tidak akan tersedia lagi untuk perjalanan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/rute/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchRute(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush