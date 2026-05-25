@extends('layouts.app')

@section('title', 'E-Tiket Digital')
@section('page_title', 'Daftar Tiket Lunas / Siap Cetak')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari Kode E-Tiket / Nama...">
    </div>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Kode E-Tiket</th>
                    <th class="px-6 py-4">Informasi Penumpang</th>
                    <th class="px-6 py-4">Perjalanan & Kursi</th>
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
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let currentSearch = '';

    $(document).ready(function() {
        fetchTiketDigital(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchTiketDigital(1, currentSearch), 400);
        });
    });

    function formatTanggal(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function fetchTiketDigital(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.tiket-digital.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-qrcode text-2xl block mb-2 opacity-50"></i> Belum ada tiket yang lunas / siap cetak</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let urlCetak = `/admin/tiket-digital/${item.id}/cetak`;
                        
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-qrcode text-primary/50 text-xl"></i>
                                        <p class="font-mono text-sm font-bold text-gray-800">${item.kode_tiket}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800 text-sm">${item.penumpang.nama}</p>
                                    <p class="text-xs text-secondary mt-0.5">${item.penumpang.nik}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-800">${item.jadwal.rute.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-gray-400 text-[10px]"></i> ${item.jadwal.rute.kota_tujuan}</p>
                                    <p class="text-xs text-secondary mt-1"><i class="fa-regular fa-calendar mr-1"></i> ${formatTanggal(item.jadwal.tanggal)} | Kursi: <span class="font-bold text-primary">${item.kursi.nomor_kursi}</span></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border bg-success/10 text-success border-success/20">Siap Cetak</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="${urlCetak}" target="_blank" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm transition">
                                        <i class="fa-solid fa-print"></i> Cetak / PDF
                                    </a>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> tiket lunas</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchTiketDigital(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchTiketDigital(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchTiketDigital(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }
</script>
@endpush