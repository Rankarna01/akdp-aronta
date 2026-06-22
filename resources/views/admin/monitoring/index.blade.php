@extends('layouts.app')

@section('title', 'Monitoring Perjalanan')
@section('page_title', 'Titik Pantau Armada')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari armada atau lokasi...">
    </div>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Informasi Armada & Rute</th>
                    <th class="px-6 py-4">Posisi Terkini</th>
                    <th class="px-6 py-4">Waktu Lapor</th>
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

<!-- Modal Edit Status -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="edit-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-base">Edit Status Perjalanan</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="edit-form" onsubmit="submitEdit(event)">
            <input type="hidden" id="edit-id">
            <input type="hidden" id="edit-jadwal-id" name="jadwal_id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status</label>
                    <select id="edit-status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" required>
                        <option value="Persiapan">Persiapan</option>
                        <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                        <option value="Sampai">Sampai</option>
                        <option value="Kendala">Kendala</option>
                    </select>
                </div>
                <div id="edit-keterangan-group" class="hidden">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Keterangan Kendala</label>
                    <textarea id="edit-keterangan" name="keterangan" rows="3" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Jelaskan kendala yang dialami..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Simpan Perubahan</button>
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
        fetchMonitoring(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchMonitoring(1, currentSearch), 400);
        });
    });

    function formatWaktuUpdate(dateString) {
        let date = new Date(dateString);
        return date.toLocaleString('id-ID', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }

    $('#edit-status').on('change', function() {
        if ($(this).val() === 'Kendala') {
            $('#edit-keterangan-group').removeClass('hidden');
            $('#edit-keterangan').prop('required', true);
        } else {
            $('#edit-keterangan-group').addClass('hidden');
            $('#edit-keterangan').prop('required', false);
            $('#edit-keterangan').val('');
        }
    });

    function closeEditModal() {
        $('#edit-card').removeClass('scale-100 translate-y-0').addClass('scale-95');
        setTimeout(() => { $('#edit-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function fetchMonitoring(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.monitoring.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-map-location text-2xl block mb-2 opacity-50"></i> Belum ada data pergerakan armada</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = '';
                        if(item.status === 'Persiapan') badgeColor = 'bg-gray-100 text-gray-600';
                        else if(item.status === 'Dalam Perjalanan') badgeColor = 'bg-primary/10 text-primary';
                        else if(item.status === 'Sampai') badgeColor = 'bg-success/10 text-success';
                        else badgeColor = 'bg-danger/10 text-danger';

                        let ket = item.keterangan ? `<p class="text-[11px] text-gray-500 mt-1 italic">"${item.keterangan}"</p>` : '';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800"><i class="fa-solid fa-bus text-primary mr-1 w-4"></i> ${item.jadwal.armada.nama_bus}</p>
                                    <p class="text-xs font-medium text-secondary mt-1">${item.jadwal.rute.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-[10px]"></i> ${item.jadwal.rute.kota_tujuan}</p>
                                </td>
                                <td class="px-6 py-4">
                                    ${ket}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                    ${formatWaktuUpdate(item.created_at)}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold ${badgeColor}">${item.status}</span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteMonitoring(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
            paginationHtml += `<button onclick="fetchMonitoring(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchMonitoring(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchMonitoring(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }


    function openEditModal(id) {
        $.ajax({
            url: `/admin/monitoring/${id}/edit`,
            type: "GET",
            success: function(response) {
                $('#edit-id').val(response.id);
                $('#edit-jadwal-id').val(response.jadwal_id);
                $('#edit-status').val(response.status).trigger('change');
                $('#edit-keterangan').val(response.keterangan);
                
                $('#edit-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#edit-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function submitEdit(e) {
        e.preventDefault();
        let id = $('#edit-id').val();
        let formData = $('#edit-form').serialize() + '&_method=PUT';
        
        $.ajax({
            url: `/admin/monitoring/${id}`,
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeEditModal();
                    Swal.fire({ icon: 'success', title: 'Terupdate!', text: response.message, timer: 1500, showConfirmButton: false });
                    fetchMonitoring(currentPage, currentSearch);
                }
            }
        });
    }

    function deleteMonitoring(id) {
        Swal.fire({
            title: 'Hapus Catatan?',
            text: "Data posisi yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/monitoring/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchMonitoring(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush