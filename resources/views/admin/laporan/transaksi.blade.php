@extends('layouts.app')

@section('title', 'Laporan Transaksi')
@section('page_title', 'Laporan Keuangan & Transaksi')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="grid grid-cols-1 mb-6">
    <div class="bg-gradient-to-r from-primary to-blue-700 rounded-xl p-6 shadow-lg text-white flex items-center justify-between relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-10">
            <i class="fa-solid fa-wallet text-9xl"></i>
        </div>
        <div class="relative z-10">
            <p class="text-blue-100 text-sm font-medium mb-1 uppercase tracking-wider">Total Pendapatan (Lunas)</p>
            <h2 id="total-pendapatan" class="text-3xl sm:text-4xl font-bold">Rp 0</h2>
            <p class="text-xs text-blue-200 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Berdasarkan filter yang diterapkan di bawah.</p>
        </div>
    </div>
</div>

<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6">
    <form id="filter-form" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Mulai</label>
            <input type="date" id="start_date" name="start_date" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Akhir</label>
            <input type="date" id="end_date" name="end_date" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status</label>
            <select id="status" name="status" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                <option value="">Semua Status</option>
                <option value="Lunas">Lunas</option>
                <option value="Pending">Pending</option>
                <option value="Ditolak">Ditolak</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Metode</label>
            <select id="metode_pembayaran" name="metode_pembayaran" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                <option value="">Semua Metode</option>
                <option value="Transfer Bank BCA">BCA</option>
                <option value="Transfer Bank Mandiri">Mandiri</option>
                <option value="Transfer Bank BRI">BRI</option>
                <option value="E-Wallet DANA">DANA</option>
                <option value="Tunai">Tunai</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <button type="button" onclick="resetFilter()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-secondary rounded-xl transition tooltip" title="Reset Filter">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-semibold text-gray-800">Rincian Transaksi</h3>
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
                    <th class="px-6 py-4">Tgl. Transaksi</th>
                    <th class="px-6 py-4">Kode & Penumpang</th>
                    <th class="px-6 py-4">Metode Bayar</th>
                    <th class="px-6 py-4 text-right">Nominal (Rp)</th>
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

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    function formatWaktu(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function fetchLaporan(page) {
        currentPage = page;
        let formData = $('#filter-form').serialize() + '&page=' + page;

        $.ajax({
            url: "{{ route('admin.laporan.transaksi.data') }}",
            type: "GET",
            data: formData,
            success: function(response) {
                // Update Total Pendapatan UI
                $('#total-pendapatan').text(formatRupiah(response.total_pendapatan));

                let dataTable = response.laporan.data;
                let meta = response.laporan;
                let htmlRows = '';

                if (dataTable.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-file-invoice-dollar text-2xl block mb-2 opacity-50"></i> Tidak ada data transaksi pada filter ini</td></tr>`;
                } else {
                    dataTable.forEach(function(item) {
                        let badgeColor = '';
                        if(item.status === 'Lunas') badgeColor = 'bg-success/10 text-success';
                        else if(item.status === 'Pending') badgeColor = 'bg-warning/10 text-warning';
                        else badgeColor = 'bg-danger/10 text-danger';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                    ${formatWaktu(item.tanggal_bayar)} WIB
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800 text-xs mb-1 border border-gray-200 bg-gray-50 px-2 py-0.5 rounded inline-block">${item.tiket.kode_tiket}</p>
                                    <p class="text-sm font-semibold text-primary">${item.tiket.penumpang.nama}</p>
                                    <p class="text-[10px] text-gray-500 mt-1">${item.tiket.jadwal.rute.kota_asal} - ${item.tiket.jadwal.rute.kota_tujuan}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-700"><i class="fa-solid fa-building-columns text-gray-400 mr-1 w-4"></i> ${item.metode_pembayaran}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">
                                    ${formatRupiah(item.jumlah_bayar)}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border border-white ${badgeColor}">${item.status}</span>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#table-body').html(htmlRows);
                renderPagination(meta);
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

    function cetakPDF() {
        let queryString = $('#filter-form').serialize();
        window.open(`{{ route('admin.laporan.transaksi.cetak') }}?${queryString}`, '_blank');
    }

    function exportExcel() {
        let table = document.getElementById("report-table");
        let html = table.outerHTML;
        
        let blob = new Blob([`\uFEFF${html}`], { type: "application/vnd.ms-excel;charset=utf-8" });
        let url = URL.createObjectURL(blob);
        let a = document.createElement("a");
        a.href = url;
        a.download = "Laporan_Transaksi_Aronta.xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
@endpush