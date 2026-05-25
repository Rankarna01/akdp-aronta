@extends('layouts.app')

@section('title', 'Jadwal Berangkat')
@section('page_title', 'Jadwal Keberangkatan')

@section('sidebar')
    @include('components.sidebar-admin')
@endsection

@section('content')
<div class="bg-surface rounded-xl p-5 shadow-halus border border-gray-100 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
        </span>
        <input type="text" id="search-input" class="input-modern w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Cari kota, bus, atau tanggal...">
    </div>
    <button onclick="openCreateModal()" class="w-full sm:w-auto bg-primary hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-primary/20 transition flex items-center justify-center gap-2">
        <i class="fa-regular fa-calendar-plus"></i> Tambah Jadwal
    </button>
</div>

<div class="bg-surface rounded-xl shadow-halus border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-secondary text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-4">Waktu & Tanggal</th>
                    <th class="px-6 py-4">Rute & Harga</th>
                    <th class="px-6 py-4">Armada & Supir</th>
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

<div id="jadwal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-fade-in custom-scrollbar overflow-y-auto">
    <div class="bg-surface w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 my-auto transform transition-all scale-95 duration-300" id="modal-card">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 rounded-t-2xl z-10">
            <h3 id="modal-title" class="font-semibold text-gray-800 text-base">Atur Jadwal Baru</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="jadwal-form" onsubmit="saveForm(event)">
            <input type="hidden" id="jadwal-id" name="id">
            
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pilih Rute</label>
                        <select id="rute_id" name="rute_id" onchange="autoFillHarga()" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Pilih Rute Perjalanan --</option>
                            @foreach($rute as $item)
                                <option value="{{ $item->id }}" data-harga="{{ $item->harga_dasar }}">
                                    {{ $item->kota_asal }} - {{ $item->kota_tujuan }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-rute_id"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Harga Tiket (Rp)</label>
                        <input type="number" id="harga_tiket" name="harga_tiket" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Otomatis terisi saat rute dipilih">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-harga_tiket"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Armada Bus</label>
                        <select id="armada_id" name="armada_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Pilih Bus --</option>
                            @foreach($armada as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_bus }} ({{ $item->plat_nomor }})</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-armada_id"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Supir Bertugas</label>
                        <select id="supir_id" name="supir_id" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="">-- Pilih Supir --</option>
                            @foreach($supir as $item)
                                <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-supir_id"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Tanggal Berangkat</label>
                        <input type="date" id="tanggal" name="tanggal" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-tanggal"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Jam Berangkat</label>
                        <input type="time" id="waktu_berangkat" name="waktu_berangkat" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-waktu_berangkat"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Status Jadwal</label>
                        <select id="status" name="status" class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Berangkat">Berangkat</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <span class="text-xs text-danger mt-1 hidden error-field" id="err-status"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 sticky bottom-0 rounded-b-2xl z-10">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:bg-gray-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-primary hover:bg-blue-900 text-white rounded-xl shadow-md transition">Simpan Jadwal</button>
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
        fetchJadwal(currentPage, currentSearch);

        let searchTimer;
        $('#search-input').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchJadwal(1, currentSearch), 400);
        });
    });

    // Fitur Auto Fill Harga
    function autoFillHarga() {
        let selectedOption = $('#rute_id').find('option:selected');
        let hargaDasar = selectedOption.data('harga');
        if (hargaDasar) {
            $('#harga_tiket').val(hargaDasar);
        } else {
            $('#harga_tiket').val('');
        }
    }

    // Helper formatter
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
    function formatTanggal(dateString) {
        let date = new Date(dateString);
        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }
    function formatWaktu(timeString) {
        if(!timeString) return '-';
        let time = timeString.split(':');
        return `${time[0]}:${time[1]} WIB`;
    }

    function fetchJadwal(page, search) {
        currentPage = page;
        $.ajax({
            url: "{{ route('admin.jadwal.data') }}",
            type: "GET",
            data: { page: page, search: search },
            success: function(response) {
                let htmlRows = '';
                if (response.data.length === 0) {
                    htmlRows = `<tr><td colspan="5" class="text-center py-8 text-secondary"><i class="fa-solid fa-calendar-xmark text-2xl block mb-2 opacity-50"></i> Tidak ada jadwal ditemukan</td></tr>`;
                } else {
                    response.data.forEach(function(item) {
                        let badgeColor = '';
                        if(item.status === 'Menunggu') badgeColor = 'bg-blue-50 text-blue-600 border-blue-200';
                        else if(item.status === 'Berangkat') badgeColor = 'bg-primary/10 text-primary border-primary/20';
                        else if(item.status === 'Selesai') badgeColor = 'bg-success/10 text-success border-success/20';
                        else badgeColor = 'bg-danger/10 text-danger border-danger/20';

                        htmlRows += `
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800"><i class="fa-regular fa-calendar text-primary mr-1"></i> ${formatTanggal(item.tanggal)}</p>
                                    <p class="text-sm font-medium text-gray-600 mt-1"><i class="fa-regular fa-clock text-secondary mr-1"></i> ${formatWaktu(item.waktu_berangkat)}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">${item.rute.kota_asal} <i class="fa-solid fa-arrow-right mx-1 text-gray-400 text-[10px]"></i> ${item.rute.kota_tujuan}</p>
                                    <p class="text-xs font-semibold text-primary mt-1">${formatRupiah(item.harga_tiket)}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-800"><i class="fa-solid fa-bus text-secondary mr-1 w-4"></i> ${item.armada.nama_bus}</p>
                                    <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-id-card text-secondary mr-1 w-4"></i> Supir: <span class="font-semibold">${item.supir.user.name}</span></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border ${badgeColor}">${item.status}</span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-1">
                                    <button onclick="openEditModal(${item.id})" class="text-primary hover:bg-primary/10 p-2 rounded-lg transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button onclick="deleteJadwal(${item.id})" class="text-danger hover:bg-danger/10 p-2 rounded-lg transition" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
        let paginationHtml = `<p class="text-xs text-secondary">Menampilkan <span class="font-semibold text-gray-700">${meta.from ?? 0}</span> sampai <span class="font-semibold text-gray-700">${meta.to ?? 0}</span> dari <span class="font-semibold text-gray-700">${meta.total}</span> jadwal</p>`;
        
        if (meta.last_page > 1) {
            paginationHtml += `<div class="flex items-center gap-1">`;
            paginationHtml += `<button onclick="fetchJadwal(${meta.current_page - 1}, currentSearch)" ${meta.current_page === 1 ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Sebelumnya</button>`;
            for (let i = 1; i <= meta.last_page; i++) {
                if(i === meta.current_page) {
                    paginationHtml += `<button class="px-3 py-1.5 rounded-lg bg-primary text-white font-medium text-xs">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="fetchJadwal(${i}, currentSearch)" class="px-3 py-1.5 rounded-lg text-secondary hover:bg-gray-200 text-xs transition">${i}</button>`;
                }
            }
            paginationHtml += `<button onclick="fetchJadwal(${meta.current_page + 1}, currentSearch)" ${meta.current_page === meta.last_page ? 'disabled class="px-2.5 py-1.5 rounded-lg text-gray-300 cursor-not-allowed"' : 'class="px-2.5 py-1.5 rounded-lg text-secondary hover:bg-gray-200 transition"'}>Selanjutnya</button>`;
            paginationHtml += `</div>`;
        }
        $('#pagination-container').html(paginationHtml);
    }

    function openCreateModal() {
        $('#jadwal-form')[0].reset();
        $('#jadwal-id').val('');
        $('.error-field').addClass('hidden').html('');
        $('#modal-title').text('Tambah Jadwal Keberangkatan');
        
        $('#jadwal-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
    }

    function openEditModal(id) {
        $('.error-field').addClass('hidden').html('');
        $.ajax({
            url: `/admin/jadwal/${id}/edit`,
            type: "GET",
            success: function(data) {
                $('#jadwal-id').val(data.id);
                $('#rute_id').val(data.rute_id);
                $('#armada_id').val(data.armada_id);
                $('#supir_id').val(data.supir_id);
                $('#tanggal').val(data.tanggal);
                // potong detik dari waktu (misal "12:30:00" jadi "12:30")
                $('#waktu_berangkat').val(data.waktu_berangkat.substring(0, 5));
                $('#harga_tiket').val(data.harga_tiket);
                $('#status').val(data.status);
                
                $('#modal-title').text('Edit Jadwal Keberangkatan');
                
                $('#jadwal-modal').removeClass('hidden').addClass('flex');
                setTimeout(() => { $('#modal-card').removeClass('scale-95').addClass('scale-100'); }, 50);
            }
        });
    }

    function closeModal() {
        $('#modal-card').removeClass('scale-100').addClass('scale-95');
        setTimeout(() => { $('#jadwal-modal').removeClass('flex').addClass('hidden'); }, 150);
    }

    function saveForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');
        
        let id = $('#jadwal-id').val();
        let url = id ? `/admin/jadwal/${id}` : "{{ route('admin.jadwal.store') }}";
        let type = id ? "PUT" : "POST";
        let formData = $('#jadwal-form').serialize();

        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                if (response.success) {
                    closeModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false });
                    fetchJadwal(currentPage, currentSearch);
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

    function deleteJadwal(id) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: "Jadwal yang dihapus akan dibatalkan secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/jadwal/${id}`,
                    type: "DELETE",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false });
                            fetchJadwal(currentPage, currentSearch);
                        }
                    }
                });
            }
        });
    }
</script>
@endpush