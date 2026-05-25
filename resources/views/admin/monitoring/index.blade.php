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
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-solid fa-location-dot"></i> Update Posisi
    </button>
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

<div id="monitoring-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 rounded-t-2xl z-10">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Update Posisi Armada</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="monitoring-form" onsubmit="saveForm(event)">
            <input type="hidden" id="monitoring-id" name="id">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Perjalanan (Jadwal Aktif)</label>
                    <select id="jadwal_id" name="jadwal_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <option value="">-- Pilih Armada yang Sedang Beroperasi --</option>
                        @foreach($jadwalAktif as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->armada->nama_bus }} | {{ $item->rute->kota_asal }} - {{ $item->rute->kota_tujuan }} ({{ \Carbon\Carbon::parse($item->tanggal)->format('d M') }})
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-jadwal_id"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Lokasi Sekarang</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-map-pin"></i></span>
                        <input type="text" id="lokasi_sekarang" name="lokasi_sekarang" class="input-modern w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Contoh: RM. Siang Malam, Tebing Tinggi">
                    </div>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-lokasi_sekarang"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Kondisi / Status Perjalanan</label>
                    <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <option value="Persiapan">Persiapan (Pool)</option>
                        <option value="Di Perjalanan">Di Perjalanan (Lancar)</option>
                        <option value="Istirahat">Istirahat (Rest Area)</option>
                        <option value="Kendala">Terkendala (Macet/Trouble)</option>
                        <option value="Tiba">Tiba di Tujuan</option>
                    </select>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Keterangan Tambahan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="input-modern w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Opsional (Misal: Estimasi telat 30 menit)"></textarea>
                    <span class="text-xs text-danger mt-1 hidden error-field" id="err-keterangan"></span>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 rounded-b-2xl z-10">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Update Posisi</button>
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
                        else if(item.status === 'Di Perjalanan') badgeColor = 'bg-primary/10 text-primary';
                        else if(item.status === 'Istirahat') badgeColor = 'bg-blue-50 text-blue-600';
                        else if(item.status === 'Tiba') badgeColor = 'bg-success/10 text-success';
                        else badgeColor = 'bg-danger/10 text-danger';

                        let ket = item.keterangan ? `<p class="text-[11px] text-gray-500 mt-1 italic">"${item.keterangan}"</p>` : '';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800"><i class="fa-solid fa-bus text-primary mr-1 w-4"></i> ${item.jadwal.armada.nama_bus}</p>
                                    <p class="text-xs font-medium text-secondary mt-1">${item.jadwal.rute.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-[10px]"></i> ${item.jadwal.rute.kota_tujuan}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800"><i class="fa-solid fa-location-dot text-danger mr-1"></i> ${item.lokasi_sekarang}</p>
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

    function openCreateModal() {
        $('#monitoring-form')[0].reset();
        $('#monitoring-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Update Posisi Armada');
        
        $('#monitoring-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/monitoring/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#monitoring-id').val(data.id);
                // Karena jadwal dropdown hanya berisi yg 'Aktif', jika data lama sudah selesai mungkin tidak muncul.
                // Kita force select jika opsinya masih ada.
                $('#jadwal_id').val(data.jadwal_id); 
                $('#lokasi_sekarang').val(data.lokasi_sekarang);
                $('#status').val(data.status);
                $('#keterangan').val(data.keterangan);
                
                $('#modal-title').text('Edit Catatan Monitoring');
                
                $('#monitoring-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#monitoring-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#monitoring-id').val();
        let url = id ? `/admin/monitoring/${id}` : "{{ route('admin.monitoring.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#monitoring-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchMonitoring(currentPage, currentSearch);
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