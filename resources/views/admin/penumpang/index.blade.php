@extends('layouts.app')

@section('title', 'Manajemen Penumpang')
@section('page_title', 'Data Master Penumpang')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari NIK, Nama, atau No HP...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Penumpang
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Nama Penumpang</th>
                    <th class="px-6 py-4">NIK (Identitas)</th>
                    <th class="px-6 py-4">Jenis Kelamin</th>
                    <th class="px-6 py-4">Nomor HP</th>
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

<div id="penumpang-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="modal-card">
        
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Data Penumpang</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form id="penumpang-form" onsubmit="saveForm(event)">
            <input type="hidden" id="penumpang-id" name="id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nama Lengkap Penumpang</label>
                    <input type="text" id="nama" name="nama" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Ahmad Fauzi">
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-nama"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">NIK (No. KTP)</label>
                        <input type="text" inputmode="numeric" minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" id="nik" name="nik" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="16 Digit Angka" required>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-nik"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nomor HP</label>
                        <input type="number" id="no_hp" name="no_hp" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: 0812345678">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-no_hp"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <div class="flex items-center gap-6 mt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" id="jk_l" value="Laki-laki" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" id="jk_p" value="Perempuan" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-jenis_kelamin"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="2" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Alamat domisili sekarang..."></textarea>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-alamat"></span>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Simpan Penumpang</button>
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
        // Load data saat halaman pertama kali dibuka
        fetchPenumpang(currentPage, currentSearch);

        // Fitur Live Search dengan Debounce
        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchPenumpang(1, currentSearch), 400);
        });
    });

    // ==========================================
    // FUNGSI FETCH & RENDER DATA AJAX
    // ==========================================
    function fetchPenumpang(page, search) {
        currentPage = page;
        
        $.ajax({
            url: "{{ route('admin.penumpang.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                
                if (response.data.length === 0) {
                    htmlRows = `
                        <tr>
                            <td colspan="5" class="text-center py-8 text-secondary">
                                <i class="fa-solid fa-user-slash text-2xl block mb-2 opacity-50"></i> 
                                Tidak ada data penumpang ditemukan
                            </td>
                        </tr>
                    `;
                } else {
                    response.data.forEach(function(item) {
                        let iconJk = item.jenis_kelamin === 'Laki-laki' 
                            ? '<i class="fa-solid fa-mars text-blue-500 mr-1"></i>' 
                            : '<i class="fa-solid fa-venus text-pink-500 mr-1"></i>';
                        
                        // Validasi Badge
                        let badgeAkun = item.user 
                            ? `<span class="bg-primary/10 text-primary border border-primary/20 text-[9px] px-2 py-0.5 rounded-full ml-2 uppercase font-bold tracking-wider" title="${item.user.email}"><i class="fa-solid fa-mobile-screen-button mr-1"></i>Akun App</span>` 
                            : `<span class="bg-gray-100 text-gray-500 border border-gray-200 text-[9px] px-2 py-0.5 rounded-full ml-2 uppercase font-bold tracking-wider"><i class="fa-solid fa-user-pen mr-1"></i>Manual</span>`;

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center font-semibold text-gray-800">
                                        ${item.nama} ${badgeAkun}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs text-gray-600 bg-gray-50 inline-block px-2 py-1 rounded-lg border border-gray-100">
                                        ${item.nik}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${iconJk} ${item.jenis_kelamin}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">
                                    ${item.no_hp}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button onclick="deletePenumpang(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
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

    // ==========================================
    // RENDER NAVIGASI PAGINASI DINAMIS
    // ==========================================
    function renderPagination(meta) {
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> data penumpang</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            
            // Tombol Sebelumnya
            let prevBtnClass = meta.current_page === 1 
                ? 'px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed' 
                : 'px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition';
            let prevDisabled = meta.current_page === 1 ? 'disabled' : '';
            
            paginationHtml += `<button onclick="fetchPenumpang(${meta.current_page - 1}, currentSearch)" class="${prevBtnClass}" ${prevDisabled}>Sebelumnya</button>`;
            
            // Nomor Halaman
            for (let i = 1; i <= meta.last_page; i++) {
                if (i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchPenumpang(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            
            // Tombol Selanjutnya
            let nextBtnClass = meta.current_page === meta.last_page 
                ? 'px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed' 
                : 'px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition';
            let nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
            
            paginationHtml += `<button onclick="fetchPenumpang(${meta.current_page + 1}, currentSearch)" class="${nextBtnClass}" ${nextDisabled}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        
        $('#pagination-container').html(paginationHtml);
    }

    // ==========================================
    // FUNGSI MANAJEMEN MODAL
    // ==========================================
    function openCreateModal() {
        $('#penumpang-form')[0].reset();
        $('#penumpang-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Penumpang Baru');
        
        $('#penumpang-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        
        $.ajax({
            url: `/admin/penumpang/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#penumpang-id').val(data.id);
                $('#nama').val(data.nama);
                $('#nik').val(data.nik);
                $('#no_hp').val(data.no_hp);
                $('#alamat').val(data.alamat);
                
                if (data.jenis_kelamin === 'Laki-laki') {
                    $('#jk_l').prop('checked', true);
                } else {
                    $('#jk_p').prop('checked', true);
                }
                
                $('#modal-title').text('Edit Informasi Penumpang');
                $('#penumpang-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#penumpang-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    // ==========================================
    // FUNGSI SUBMIT FORM & DELETE
    // ==========================================
    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#penumpang-id').val();
        let url = id ? `/admin/penumpang/${id}` : "{{ route('admin.penumpang.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#penumpang-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Berhasil!', 
                        text: response.message, 
                        timer: 2000, 
                        showConfirmButton: false 
                    });
                    fetchPenumpang(currentPage, currentSearch);
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

    function deletePenumpang(id) {
        Swal.fire({
            title: 'Hapus Data Penumpang?',
            text: "Data riwayat pesanan tiket mungkin akan terpengaruh!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/penumpang/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Terhapus!', 
                                text: response.message, 
                                timer: 1500, 
                                showConfirmButton: false 
                            });
                            fetchPenumpang(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush