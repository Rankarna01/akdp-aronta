@extends('layouts.app')

@section('title', 'Data Metode Pembayaran')
@section('page_title', 'Kelola Bank & E-Wallet Tujuan Transfer')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari Nama Bank / Rekening...">
    </div>
    <button onclick="openModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Metode
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Nama Bank / E-Wallet</th>
                    <th class="px-6 py-4">Nomor Rekening</th>
                    <th class="px-6 py-4">Atas Nama</th>
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

<!-- Modal Pop-up Modern -->
<div id="metode-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl border border-gray-100 transform transition-all scale-95 duration-300 relative" id="modal-card">
        
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between rounded-t-2xl">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Tambah Metode Pembayaran</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="metode-form" onsubmit="saveForm(event)">
            @csrf
            <input type="hidden" id="metode-id" name="id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nama Bank / E-Wallet</label>
                    <input type="text" id="nama_bank" name="nama_bank" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: Bank BCA / DANA" required>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-nama_bank"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nomor Rekening / HP</label>
                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="nomor_rekening" name="nomor_rekening" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: 1234567890" required>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-nomor_rekening"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Atas Nama</label>
                    <input type="text" id="atas_nama" name="atas_nama" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: PT Aronta Citra Persada" required>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-atas_nama"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status</label>
                    <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
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
        fetchMetode(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchMetode(1, currentSearch), 400);
        });
    });

    function fetchMetode(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.metode-pembayaran-master.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-money-check-dollar text-2xl block mb-2 opacity-50"></i> Belum ada data metode pembayaran</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let statusBadge = item.status === 'Aktif' 
                            ? '<span class="px-2.5 py-1 rounded-full text-[11px] font-bold border bg-success/10 text-success border-success/20">Aktif</span>' 
                            : '<span class="px-2.5 py-1 rounded-full text-[11px] font-bold border bg-gray-100 text-gray-500 border-gray-200">Nonaktif</span>';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-semibold text-gray-800 text-sm">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-building-columns text-primary/50"></i>
                                        ${item.nama_bank}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-mono text-sm font-bold tracking-wider">${item.nomor_rekening}</td>
                                <td class="px-6 py-4 text-gray-700 font-semibold text-sm uppercase">${item.atas_nama}</td>
                                <td class="px-6 py-4 text-center">${statusBadge}</td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="editMetode(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteMetode(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
            paginationHtml += `<button onclick="fetchMetode(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchMetode(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchMetode(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openModal() {
        $('#metode-form')[0].reset();
        $('#metode-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Metode Pembayaran');
        
        $('#metode-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function editMetode(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/metode-pembayaran-master/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#metode-id').val(data.id);
                $('#nama_bank').val(data.nama_bank);
                $('#nomor_rekening').val(data.nomor_rekening);
                $('#atas_nama').val(data.atas_nama);
                $('#status').val(data.status);
                
                $('#modal-title').text('Edit Metode Pembayaran');
                
                $('#metode-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#metode-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#metode-id').val();
        let url = id ? `/admin/metode-pembayaran-master/${id}` : "{{ route('admin.metode-pembayaran-master.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#metode-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchMetode(currentPage, currentSearch);
                }
            },
            error: function(jqxhr) {
                if (jqxhr.status === 422) {
                    let errors = jqxhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        $(`#err-${key}`).removeClass('hidden').text(val[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan sistem.' });
                }
            }
        });
    }

    function deleteMetode(id) {
        Swal.fire({
            title: 'Hapus Metode?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/metode-pembayaran-master/${id}`,
                    type: "DELETE",
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchMetode(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
