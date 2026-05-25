@extends('layouts.app')

@section('title', 'Manajemen Kursi')
@section('page_title', 'Pengaturan Kursi & Visualisasi')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')

<div id="bus-grid-view">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">Pilih Armada Bus</h2>
        <p class="text-sm text-secondary">Pilih bus di bawah ini untuk mengatur denah dan jumlah kursinya.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($armada as $bus)
            <div onclick="openBusDetail({{ $bus->id }}, '{{ $bus->nama_bus }}', '{{ $bus->plat_nomor }}')" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-primary hover:shadow-md cursor-pointer transition-all group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-primary opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-14 h-14 bg-blue-50 text-primary rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bus"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg">{{ $bus->nama_bus }}</h3>
                <p class="text-xs text-secondary mt-1 font-mono font-semibold bg-gray-100 inline-block px-2 py-1 rounded">{{ $bus->plat_nomor }}</p>
                
                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between text-xs font-bold text-gray-500 group-hover:text-primary transition-colors">
                    <span>Atur Kursi</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-8 text-center border border-gray-100">
                <i class="fa-solid fa-bus-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Belum ada data armada bus aktif.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="seat-table-view" class="hidden animate-fade-in">
    
    <div class="flex items-center gap-4 mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <button onclick="backToGrid()" class="w-10 h-10 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div>
            <h2 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <span id="selected-bus-name">Nama Bus</span> 
                <span id="selected-bus-plat" class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded font-mono">Plat</span>
            </h2>
            <p class="text-[11px] text-secondary">Manajemen kursi spesifik untuk armada ini</p>
        </div>
    </div>

    <div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
            </span>
            <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari nomor kursi...">
        </div>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <button onclick="openVisualModal()" class="flex-1 sm:flex-none bg-blue-50 hover:bg-blue-100 text-primary border border-blue-200 text-sm font-medium px-4 py-2 rounded-xl transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-map"></i> Lihat Denah
            </button>
            <button onclick="openGenerateModal()" class="flex-1 sm:flex-none bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Kursi
            </button>
        </div>
    </div>

    <div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                        <th class="px-6 py-4">Nomor Kursi</th>
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
</div>

<div id="generate-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="generate-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-base"><i class="fa-solid fa-wand-magic-sparkles text-primary mr-2"></i>Generate Kursi</h3>
            <button type="button" onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="generate-form" onsubmit="submitGenerate(event)">
            <input type="hidden" name="armada_id" id="gen_armada_id">
            
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl mb-2">
                    <p class="text-[10px] text-primary font-medium leading-relaxed">Peringatan: Meng-generate kursi baru akan <b>menghapus dan me-reset</b> seluruh data kursi yang sudah ada pada <span id="gen_bus_name" class="font-bold"></span>.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Jumlah Total Kursi</label>
                    <input type="number" name="jumlah_kursi" value="25" min="10" max="60" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-800">
                    <p class="text-[10px] text-gray-400 mt-1">Batas lorong sampai baris ke-5. Sisanya ditumpuk di baris akhir.</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModals()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Generate Sekarang</button>
            </div>
        </form>
    </div>
</div>

<div id="visual-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="visual-card">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white rounded-t-3xl z-20">
            <h3 class="font-bold text-gray-800 text-base"><i class="fa-solid fa-map text-primary mr-2"></i>Denah <span id="vis_bus_name"></span></h3>
            <button type="button" onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <div class="p-6 bg-gray-50/50">
            <div id="bus-body-container" class="hidden">
                <div class="max-w-[340px] mx-auto bg-white border-[6px] border-gray-300 rounded-[3.5rem] p-6 pt-10 pb-10 relative shadow-xl">
                    <div id="seat-grid-container" class="mt-2">
                        </div>
                </div>
                
                <div class="flex items-center justify-center gap-6 mt-8">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded border-2 border-primary bg-blue-50"></div>
                        <span class="text-xs font-bold text-gray-600">Aktif</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded border-2 border-danger bg-red-50"></div>
                        <span class="text-xs font-bold text-gray-600">Rusak/Non-Aktif</span>
                    </div>
                </div>
            </div>

            <div id="bus-empty-state" class="py-12 text-center text-secondary">
                <i class="fa-solid fa-chair text-4xl mb-3 opacity-30"></i>
                <p class="text-sm">Kursi belum di-generate untuk bus ini.</p>
            </div>
        </div>
    </div>
</div>

<div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-surface w-full max-w-sm rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform transition-all scale-95 duration-300" id="edit-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-base">Edit Status Kursi</h3>
            <button type="button" onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="edit-form" onsubmit="submitEdit(event)">
            <input type="hidden" id="edit-id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nomor Kursi</label>
                    <input type="text" id="edit-nomor" name="nomor_kursi" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status</label>
                    <select id="edit-status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif (Rusak)</option>
                    </select>
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
    let currentArmadaId = null;
    let currentArmadaName = '';

    $(document).ready(function() {
        // Search listener khusus tabel
        $('#search-input').on('keyup', function() {
            clearTimeout(window.searchTimer);
            currentSearch = $(this).val();
            window.searchTimer = setTimeout(() => fetchKursi(1, currentSearch), 400);
        });
    });

    /* --- FUNGSI NAVIGASI VIEW --- */
    function openBusDetail(armadaId, namaBus, platNomor) {
        currentArmadaId = armadaId;
        currentArmadaName = namaBus;

        $('#selected-bus-name').text(namaBus);
        $('#selected-bus-plat').text(platNomor);

        $('#bus-grid-view').hide();
        $('#seat-table-view').removeClass('hidden');

        // Muat tabel kursinya
        fetchKursi(1, '');
    }

    function backToGrid() {
        currentArmadaId = null;
        currentSearch = '';
        $('#search-input').val('');
        
        $('#seat-table-view').addClass('hidden');
        $('#bus-grid-view').fadeIn();
    }

    /* --- FUNGSI TABEL AJAX --- */
    function fetchKursi(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.kursi.data') }}",
            type: "GET",
            data: { page: page, search: search, armada_id: currentArmadaId },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="3" class="text-center py-10 text-secondary"><i class="fa-solid fa-chair text-3xl block mb-2 opacity-50"></i> Kursi belum di-generate untuk armada ini.</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = item.status === 'Aktif' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger';
                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-bold text-primary"><span class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-1.5 text-xs"><i class="fa-solid fa-chair mr-2 text-blue-300"></i>${item.nomor_kursi}</span></td>
                                <td class="px-6 py-4 text-center"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold ${badgeColor}">${item.status}</span></td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id}, '${item.nomor_kursi}', '${item.status}')" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit Status"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteKursi(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> kursi</p>`;
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchKursi(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelum</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) { paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`; } 
                else { paginationHtml += `<button onclick="fetchKursi(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`; }
            }
            paginationHtml += `<button onclick="fetchKursi(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Lanjut</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    /* --- FUNGSI MANAJEMEN MODAL --- */
    function closeModals() {
        $('#generate-card, #visual-card, #edit-card').removeClass('scale-100 translate-y-0').addClass('scale-95');
        setTimeout(() => { $('#generate-modal, #visual-modal, #edit-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function openGenerateModal() {
        // Otomatis isi data form berdasarkan bus yang sedang dibuka
        $('#gen_armada_id').val(currentArmadaId);
        $('#gen_bus_name').text(currentArmadaName);

        $('#generate-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#generate-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openVisualModal() {
        $('#vis_bus_name').text(currentArmadaName);
        $('#visual-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#visual-card').removeClass('scale-95').addClass('scale-100'); }, 50);
        
        // Langsung muat denah bus yang sedang aktif
        loadBusLayout();
    }

    function openEditModal(id, nomor, status) {
        $('#edit-id').val(id);
        $('#edit-nomor').val(nomor);
        $('#edit-status').val(status);
        $('#edit-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#edit-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    /* --- FUNGSI SUBMIT AJAX --- */
    function submitGenerate(e) {
        e.preventDefault();
        let formData = $('#generate-form').serialize();
        $.ajax({
            url: "{{ route('admin.kursi.generate') }}", type: "POST", data: formData,
            success: function(response) {
                if (response.success) {
                    closeModals();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchKursi(1, '');
                }
            }
        });
    }

    function submitEdit(e) {
        e.preventDefault();
        let id = $('#edit-id').val();
        let formData = $('#edit-form').serialize() + '&_method=PUT';
        $.ajax({
            url: `/admin/kursi/${id}`, type: "POST", data: formData,
            success: function(response) {
                if (response.success) {
                    closeModals();
                    Swal.fire({ icon: 'success', title: 'Terupdate!', timer: 1500, showConfirmButton: false });
                    fetchKursi(currentPage, currentSearch);
                }
            }
        });
    }

    function deleteKursi(id) {
        Swal.fire({
            title: 'Hapus Kursi Ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#1e3a8a', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({ url: `/admin/kursi/${id}`, type: "DELETE", success: function() { fetchKursi(currentPage, currentSearch); } });
            }
        });
    }

    /* --- ALGORITMA VISUALISASI DENAH BUS KHUSUS 6 BARIS --- */
    function loadBusLayout() {
        $.ajax({
            url: `/admin/kursi/layout/${currentArmadaId}`,
            type: "GET",
            success: function(seats) {
                if (seats.length === 0) {
                    $('#bus-body-container').addClass('hidden');
                    $('#bus-empty-state').removeClass('hidden');
                    return;
                }

                $('#bus-empty-state').addClass('hidden');
                $('#bus-body-container').removeClass('hidden');

                let html = '<div class="grid grid-cols-5 gap-y-4 gap-x-3">';
                let total = seats.length;
                let i = 0;

                // --- BARIS 1: [1] [2] [Lorong] [Kosong] [Supir] ---
                if (total > 0) { html += drawSeatBlock(seats[i]); i++; } else { html += '<div></div>'; }
                if (total > i) { html += drawSeatBlock(seats[i]); i++; } else { html += '<div></div>'; }
                
                html += drawLorong(); // Lorong Tengah
                html += '<div></div>'; // Kosong (Pintu)
                html += `<div class="aspect-square flex flex-col items-center justify-center rounded-xl border-[3px] border-gray-300 bg-gray-100 text-gray-500 font-bold text-[8px] shadow-sm"><i class="fa-solid fa-steering-wheel text-xl mb-1"></i>Supir</div>`;

                // --- BARIS 2 - 5 (Maksimal 4 baris normal) ---
                let rowCount = 2;
                while (i < total && rowCount <= 5) {
                    // Kiri 2
                    html += drawSeatBlock(seats[i]); i++;
                    if (i < total) { html += drawSeatBlock(seats[i]); i++; } else { html += '<div></div>'; }
                    
                    html += drawLorong(); // Lorong Tengah
                    
                    // Kanan 2
                    if (i < total) { html += drawSeatBlock(seats[i]); i++; } else { html += '<div></div>'; }
                    if (i < total) { html += drawSeatBlock(seats[i]); i++; } else { html += '<div></div>'; }
                    
                    rowCount++;
                }

                // --- BARIS 6: SISANYA DITUMPUK (Menggunakan Flexbox) ---
                if (i < total) {
                    html += `<div class="col-span-5 flex justify-between items-center gap-2 mt-1">`;
                    while(i < total) {
                        html += `<div class="flex-1 w-full">` + drawSeatBlock(seats[i]) + `</div>`;
                        i++;
                    }
                    html += `</div>`;
                }

                html += '</div>';
                $('#seat-grid-container').html(html);
            }
        });
    }

    function drawLorong() {
        return `<div class="w-full flex items-center justify-center"><div class="w-1 h-full bg-gray-200 rounded-full opacity-50"></div></div>`;
    }

    function drawSeatBlock(seat) {
        let colorClass = seat.status === 'Aktif' 
            ? 'bg-blue-50 border-primary text-primary hover:bg-primary hover:text-white cursor-pointer' 
            : 'bg-red-50 border-danger text-danger cursor-not-allowed opacity-70';
            
        return `<div class="aspect-square w-full flex items-center justify-center rounded-xl border-[3px] shadow-sm font-bold text-sm transition ${colorClass}" title="Status: ${seat.status}">${seat.nomor_kursi}</div>`;
    }
</script>
@endpush