@extends('layouts.app')

@section('title', 'Manajemen Supir')
@section('page_title', 'Data Supir & Akun')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari nama atau NIK...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Tambah Supir
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Profil</th>
                    <th class="px-6 py-4">Kontak & Akun</th>
                    <th class="px-6 py-4">Identitas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50"></div>
</div>

<div id="supir-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 rounded-t-2xl z-10">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Data Supir</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="supir-form" onsubmit="saveForm(event)" enctype="multipart/form-data">
            <input type="hidden" id="supir-id" name="id">
            
            <div class="p-6 space-y-6">
                <div>
                    <h4 class="text-sm font-bold text-primary mb-3 border-b pb-2"><i class="fa-solid fa-user-lock mr-2"></i>Informasi Akun (Login Driver)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Nama Supir">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-name"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                            <input type="email" id="email" name="email" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="supir@aronta.com">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-email"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                            <input type="password" id="password" name="password" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Minimal 6 karakter">
                            <p class="text-[10px] text-gray-400 mt-1" id="pass-hint">Kosongkan jika tidak ingin mengubah password.</p>
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-password"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Foto Profil</label>
                            <input type="file" id="foto" name="foto" class="input-modern w-full px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-foto"></span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-primary mb-3 border-b pb-2"><i class="fa-solid fa-address-card mr-2"></i>Identitas Diri</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">No KTP (NIK)</label>
                            <input type="number" id="no_ktp" name="no_ktp" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-no_ktp"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">No SIM</label>
                            <input type="text" id="no_sim" name="no_sim" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-no_sim"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor HP</label>
                            <input type="number" id="no_hp" name="no_hp" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-no_hp"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                            <select id="status" name="status" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="2" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm"></textarea>
                            <span class="text-xs text-danger mt-1 hidden error-field" id="err-alamat"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl sticky bottom-0 z-10">
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
        fetchSupir(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchSupir(1, currentSearch), 400);
        });
    });

    function fetchSupir(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.supir.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-folder-open text-2xl block mb-2 opacity-50"></i> Tidak ada data supir ditemukan</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let fotoUrl = item.foto ? `/storage/${item.foto}` : `https://ui-avatars.com/api/?name=${item.user.name}&background=1e3a8a&color=fff`;
                        let badgeColor = item.status === 'Aktif' ? 'bg-success/10 text-success' : (item.status === 'Cuti' ? 'bg-warning/10 text-warning' : 'bg-danger/10 text-danger');
                        
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="${fotoUrl}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                        <div>
                                            <p class="font-semibold text-gray-800">${item.user.name}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-800"><i class="fa-solid fa-envelope w-4 text-gray-400"></i> ${item.user.email}</p>
                                    <p class="text-xs text-gray-800 mt-1"><i class="fa-solid fa-phone w-4 text-gray-400"></i> ${item.no_hp}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-600">NIK: <span class="font-mono text-gray-800">${item.no_ktp}</span></p>
                                    <p class="text-xs text-gray-600">SIM: <span class="font-mono text-gray-800">${item.no_sim}</span></p>
                                </td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-semibold ${badgeColor}">${item.status}</span></td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteSupir(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        // ... (Fungsi pagination sama persis seperti di index armada, cukup sesuaikan nama fungsinya menjadi fetchSupir)
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> supir</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchSupir(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchSupir(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchSupir(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#supir-form')[0].reset();
        $('#supir-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Data & Akun Supir');
        $('#pass-hint').text('Wajib diisi minimal 6 karakter.');
        
        $('#supir-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/supir/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#supir-id').val(data.id);
                $('#name').val(data.user.name);
                $('#email').val(data.user.email);
                $('#password').val(''); // Biarkan kosong
                $('#no_ktp').val(data.no_ktp);
                $('#no_sim').val(data.no_sim);
                $('#no_hp').val(data.no_hp);
                $('#alamat').val(data.alamat);
                $('#status').val(data.status);
                
                $('#modal-title').text('Edit Data Supir');
                $('#pass-hint').text('Biarkan kosong jika tidak ingin mengubah password.');
                
                $('#supir-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#supir-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#supir-id').val();
        let url = id ? `/admin/supir/${id}` : "{{ route('admin.supir.store') }}";
        
        // PENTING: Karena pakai form multipart (file upload), di AJAX harus pakai POST
        // Laravel akan membaca _method=PUT dari FormData untuk update data
        let formData = new FormData($('#supir-form')[0]);
        if(id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST', // WAJIB POST jika bawa file FormData
            data: formData,
            processData: false, // WAJIB false
            contentType: false, // WAJIB false
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchSupir(currentPage, currentSearch);
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

    function deleteSupir(id) {
        Swal.fire({
            title: 'Hapus Data & Akun?',
            text: "Data supir beserta akun login-nya akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/supir/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchSupir(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush