@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')
@section('page_title', 'Jejak Rekam Aktivitas (Audit Trail)')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-96">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari berdasarkan User, Modul, atau Keterangan...">
    </div>
    <button onclick="clearAllLogs()" class="w-full sm:w-auto bg-rose-50 hover:bg-rose-100 text-danger border border-rose-200 text-sm font-medium px-4 py-2 rounded-xl transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-trash-can"></i> Bersihkan Log
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Waktu Kejadian</th>
                    <th class="px-6 py-4">Aktor / Pengguna</th>
                    <th class="px-6 py-4">Aksi & Modul</th>
                    <th class="px-6 py-4">Keterangan Detail</th>
                </tr>
            </thead>
            <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-50">
                </tbody>
        </table>
    </div>
    <div id="pagination-container" class="px-6 py-4 flex items-center justify-between border-t border-gray-100 bg-gray-50/50"></div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';

    $(document).ready(function() {
        fetchLogs(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchLogs(1, currentSearch), 400);
        });
    });

    function formatWaktu(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
    }

    function fetchLogs(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.log-aktivitas.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="4" class="text-center py-8 text-secondary"><i class="fa-solid fa-shield-halved text-2xl block mb-2 opacity-50"></i> Belum ada rekaman aktivitas</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeAksi = '';
                        if(item.aksi === 'Create' || item.aksi === 'Login') badgeAksi = 'bg-success/10 text-success';
                        else if(item.aksi === 'Update') badgeAksi = 'bg-warning/10 text-warning';
                        else if(item.aksi === 'Delete') badgeAksi = 'bg-danger/10 text-danger';
                        else badgeAksi = 'bg-primary/10 text-primary';

                        let userName = item.user ? item.user.name : 'Sistem / Guest';
                        let userRole = item.user ? item.user.role : 'System';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                    ${formatWaktu(item.created_at)}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800 text-sm">${userName}</p>
                                    <p class="text-[10px] text-secondary mt-0.5 uppercase tracking-wide border border-gray-200 inline-block px-1.5 py-0.5 rounded bg-white">${userRole}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold ${badgeAksi} uppercase tracking-wider">${item.aksi}</span>
                                    <span class="text-xs font-semibold text-gray-600 ml-2"><i class="fa-solid fa-cube text-gray-400 mr-1 w-3"></i> ${item.modul}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 leading-relaxed">
                                    ${item.keterangan}
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> log</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchLogs(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchLogs(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchLogs(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function clearAllLogs() {
        Swal.fire({
            title: 'Bersihkan Semua Log?',
            text: "Seluruh jejak aktivitas akan dihapus permanen dari database!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Bersihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.log-aktivitas.clear') }}",
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Bersih!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchLogs(1, '');
                        }
                    }
                });
            }
        });
    }
</script>
@endpush