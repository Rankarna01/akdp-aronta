@extends('layouts.app')

@section('title', 'Manajemen Armada')
@section('page_title', 'Data Armada Bus')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari bus atau plat nomor...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Armada
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Nama Bus</th>
                    <th class="px-6 py-4">Plat Nomor</th>
                    <th class="px-6 py-4">Tipe Kelas</th>
                    <th class="px-6 py-4 text-center">Kapasitas Kursi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50">
        </div>
</div>

<div id="armada-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Data Armada</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="armada-form" onsubmit="saveForm(event)">
            <input type="hidden" id="armada-id" name="id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nama Bus / Seri</label>
                    <input type="text" id="nama_bus" name="nama_bus" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Jetbus 5 Aronta 01">
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-nama_bus"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Plat Nomor</label>
                        <input type="text" id="plat_nomor" name="plat_nomor" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: BK 1234 AB">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-plat_nomor"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Total Kursi</label>
                        <input type="number" id="total_kursi" name="total_kursi" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Maksimal 60">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-total_kursi"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tipe Kelas Bus</label>
                        <select id="tipe_bus" name="tipe_bus" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Pilih Kelas --</option>
                            <option value="Ekonomi">Ekonomi</option>
                            <option value="Bisnis">Bisnis</option>
                            <option value="Executive">Executive</option>
                            <option value="Royal Class">Royal Class</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-tipe_bus"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Operasional</label>
                        <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Aktif">Aktif</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
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
        fetchArmada(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(function() {
                fetchArmada(1, currentSearch);
            }, 400);
        });
    });

    function fetchArmada(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.armada.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="6" class="text-center py-8 text-secondary font-medium"><i class="fa-solid fa-folder-open text-2xl block mb-2 opacity-50"></i> Tidak ada data armada ditemukan</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = item.status === 'Aktif' ? 'bg-success/10 text-success' : (item.status === 'Maintenance' ? 'bg-warning/10 text-warning' : 'bg-danger/10 text-danger');
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-medium text-gray-800">${item.nama_bus}</td>
                                <td class="px-6 py-4 font-mono text-xs bg-gray-50 inline-block my-3 px-2 py-1 rounded-lg border border-gray-100 ml-6">${item.plat_nomor}</td>
                                <td class="px-6 py-4">${item.tipe_bus}</td>
                                <td class="px-6 py-4 text-center font-semibold">${item.total_kursi}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-medium ${badgeColor}">${item.status}</span></td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteArmada(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> armada</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchArmada(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchArmada(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            
            paginationHtml += `<button onclick="fetchArmada(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#armada-form')[0].reset();
        $('#armada-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Data Armada Baru');
        $('#armada-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/armada/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#armada-id').val(data.id);
                $('#nama_bus').val(data.nama_bus);
                $('#plat_nomor').val(data.plat_nomor);
                $('#total_kursi').val(data.total_kursi);
                $('#tipe_bus').val(data.tipe_bus);
                $('#status').val(data.status);
                
                $('#modal-title').text('Ubah Informasi Data Armada');
                $('#armada-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#armada-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#armada-id').val();
        let url = id ? `/admin/armada/${id}` : "{{ route('admin.armada.store') }}"; 
        
        let type = id ? "PUT" : "POST";
        let formData = $('#armada-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchArmada(currentPage, currentSearch);
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

    function deleteArmada(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data armada yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/armada/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchArmada(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush