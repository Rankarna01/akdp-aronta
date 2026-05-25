@extends('layouts.app')

@section('title', 'Laporan Perjalanan')
@section('page_title', 'Laporan Operasional Perjalanan')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6">
    <form id="filter-form" class="flex flex-col md:flex-row items-end gap-4">
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Mulai</label>
            <input type="date" id="start_date" name="start_date" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Akhir</label>
            <input type="date" id="end_date" name="end_date" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Perjalanan</label>
            <select id="status" name="status" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                <option value="">Semua Status</option>
                <option value="Selesai">Selesai</option>
                <option value="Berangkat">Berangkat</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Dibatalkan">Dibatalkan</option>
            </select>
        </div>
        <div class="w-full md:w-1/4 flex gap-2">
            <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition">
                <i class="fa-solid fa-filter mr-1"></i> Terapkan
            </button>
            <button type="button" onclick="resetFilter()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-secondary rounded-xl transition tooltip" title="Reset Filter">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-semibold text-gray-800">Hasil Laporan</h3>
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
                    <th class="px-6 py-4">Tanggal & Waktu</th>
                    <th class="px-6 py-4">Rute Perjalanan</th>
                    <th class="px-6 py-4">Armada & Supir</th>
                    <th class="px-6 py-4 text-center">Penumpang</th>
                    <th class="px-6 py-4 text-center">Status</th>
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
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function fetchLaporan(page) {
        currentPage = page;
        let formData = $('#filter-form').serialize() + '&page=' + page;

        $.ajax({
            url: "{{ route('admin.laporan.perjalanan.data') }}",
            type: "GET",
            data: formData,
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-folder-open text-2xl block mb-2 opacity-50"></i> Tidak ada data perjalanan pada filter ini</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = '';
                        if(item.status === 'Selesai') badgeColor = 'bg-success/10 text-success';
                        else if(item.status === 'Berangkat') badgeColor = 'bg-primary/10 text-primary';
                        else if(item.status === 'Menunggu') badgeColor = 'bg-blue-50 text-blue-600';
                        else badgeColor = 'bg-danger/10 text-danger';

                        let kapasitas = item.armada.total_kursi;
                        let persen = item.tiket_count > 0 ? Math.round((item.tiket_count / kapasitas) * 100) : 0;

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">${formatTanggal(item.tanggal)}</p>
                                    <p class="text-xs text-secondary mt-1"><i class="fa-regular fa-clock"></i> ${item.waktu_berangkat.substring(0,5)} WIB</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800 text-sm">${item.rute.kota_asal} - ${item.rute.kota_tujuan}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800"><i class="fa-solid fa-bus text-gray-400 mr-1"></i> ${item.armada.nama_bus}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-id-card text-gray-400 mr-1"></i> ${item.supir.user.name}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <p class="font-bold text-primary text-lg">${item.tiket_count}<span class="text-xs text-gray-400 font-normal">/${kapasitas}</span></p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Kapasitas ${persen}%</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border border-white ${badgeColor}">${item.status}</span>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> - <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> data</p>`;
        
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

    // Fungsi Cetak PDF
    function cetakPDF() {
        let queryString = $('#filter-form').serialize();
        window.open(`{{ route('admin.laporan.perjalanan.cetak') }}?${queryString}`, '_blank');
    }

    // Fungsi Export Excel via Javascript (Vanilla)
    function exportExcel() {
        let table = document.getElementById("report-table");
        let html = table.outerHTML;
        
        // Buat file Blob murni
        let blob = new Blob([`\uFEFF${html}`], {
            type: "application/vnd.ms-excel;charset=utf-8"
        });
        
        let url = URL.createObjectURL(blob);
        let a = document.createElement("a");
        a.href = url;
        a.download = "Laporan_Perjalanan_Aronta.xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
@endpush