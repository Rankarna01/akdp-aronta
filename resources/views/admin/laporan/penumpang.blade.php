@extends('layouts.app')

@section('title', 'Laporan Penumpang')
@section('page_title', 'Laporan & Manifest Penumpang')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6">
    <form id="filter-form" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Keberangkatan</label>
            <input type="date" id="tanggal" name="tanggal" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Filter Perjalanan Khusus</label>
            <select id="jadwal_id" name="jadwal_id" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                <option value="">-- Semua Jadwal Perjalanan --</option>
                @foreach($jadwal as $item)
                    <option value="{{ $item->id }}">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }} | {{ $item->armada->nama_bus }} ({{ $item->rute->kota_asal }} - {{ $item->rute->kota_tujuan }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition">
                <i class="fa-solid fa-filter mr-1"></i> Cari
            </button>
            <button type="button" onclick="resetFilter()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-secondary rounded-xl transition tooltip" title="Reset Filter">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-semibold text-gray-800">Daftar Manifest Penumpang</h3>
        <div class="flex gap-2">
            <button onclick="exportExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </button>
            <button onclick="cetakPDF()" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto w-full">
        <table id="report-table" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Nomor Kursi</th>
                    <th class="px-6 py-4">Nama Penumpang</th>
                    <th class="px-6 py-4">Kontak / NIK</th>
                    <th class="px-6 py-4">Jadwal & Armada</th>
                    <th class="px-6 py-4">Status</th>
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

    $(document).ready(function() {
        fetchLaporan(currentPage);

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            fetchLaporan(1);
        });
    });

    function resetFilter() {
        $('#filter-form')[0].reset();
        fetchLaporan(1);
    }

    function formatTanggal(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function fetchLaporan(page) {
        currentPage = page;
        let formData = $('#filter-form').serialize() + '&page=' + page;

        $.ajax({
            url: "{{ route('admin.laporan.penumpang.data') }}",
            type: "GET",
            data: formData,
            success: function(response) {
                let htmlRows = '';

                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-users-slash text-2xl block mb-2 opacity-50"></i> Tidak ada manifest penumpang pada filter ini</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 border border-gray-200 text-gray-800 font-bold px-3 py-1.5 rounded-lg text-sm"><i class="fa-solid fa-chair text-gray-400 mr-1"></i> ${item.kursi.nomor_kursi}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">${item.penumpang.nama}</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase">${item.penumpang.jenis_kelamin}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-700"><i class="fa-solid fa-phone text-gray-400 w-4"></i> ${item.penumpang.no_hp}</p>
                                    <p class="text-xs text-gray-500 mt-1 font-mono"><i class="fa-solid fa-id-card text-gray-400 w-4"></i> ${item.penumpang.nik}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-primary mb-1">${formatTanggal(item.jadwal.tanggal)} | ${item.jadwal.armada.nama_bus}</p>
                                    <p class="text-[10px] text-gray-500">${item.jadwal.rute.kota_asal} - ${item.jadwal.rute.kota_tujuan}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border border-white bg-success/10 text-success"><i class="fa-solid fa-check mr-1"></i> Valid</span>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> - <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> penumpang</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchLaporan(${meta.current_page - 1})" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchLaporan(${i})" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchLaporan(${meta.current_page + 1})" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function cetakPDF() {
        let queryString = $('#filter-form').serialize();
        window.open(`{{ route('admin.laporan.penumpang.cetak') }}?${queryString}`, '_blank');
    }

    function exportExcel() {
        let table = document.getElementById("report-table");
        let html = table.outerHTML;
        
        let blob = new Blob([`\uFEFF${html}`], { type: "application/vnd.ms-excel;charset=utf-8" });
        let url = URL.createObjectURL(blob);
        let a = document.createElement("a");
        a.href = url;
        a.download = "Manifest_Penumpang_Aronta.xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
@endpush