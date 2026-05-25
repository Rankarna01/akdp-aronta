@extends('layouts.app-driver')

@section('title', 'Profile Saya')

@section('content')
<div class="bg-primary rounded-b-[2rem] pt-12 pb-24 px-6 relative text-center shrink-0">
    <h1 class="text-white text-base font-bold tracking-wide">Profile Saya</h1>
</div>

<div class="px-6 -mt-16 relative z-10 text-center">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex flex-col items-center">
        <div class="relative">
            @php
                $fotoUrl = $supir->foto ? asset('storage/' . $supir->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=1e3a8a&color=fff';
            @endphp
            <img src="{{ $fotoUrl }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
            <span class="absolute bottom-1 right-1 w-4 h-4 bg-success border-2 border-white rounded-full"></span>
        </div>

        <h2 class="text-base font-bold text-gray-800 mt-3">{{ Auth::user()->name }}</h2>
        <span class="bg-success/10 text-success text-[10px] font-bold px-2.5 py-0.5 rounded-full mt-1.5 uppercase border border-success/20">
            {{ $supir->status }}
        </span>
    </div>
</div>

<div class="px-6 mt-6 space-y-3">
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-50 flex items-center gap-4">
        <div class="text-gray-400 text-base w-5 text-center"><i class="fa-solid fa-phone"></i></div>
        <div class="flex-1">
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">No. Handphone</p>
            <p class="text-xs font-bold text-gray-800 mt-0.5">{{ $supir->no_hp }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-50 flex items-center gap-4">
        <div class="text-gray-400 text-base w-5 text-center"><i class="fa-solid fa-envelope"></i></div>
        <div class="flex-1">
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Email</p>
            <p class="text-xs font-bold text-gray-800 mt-0.5">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-50 flex items-center gap-4">
        <div class="text-gray-400 text-base w-5 text-center"><i class="fa-solid fa-id-card"></i></div>
        <div class="flex-1">
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">No. SIM</p>
            <p class="text-xs font-bold text-gray-800 mt-0.5 font-mono">{{ $supir->no_sim }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-50 flex items-center gap-4">
        <div class="text-gray-400 text-base w-5 text-center"><i class="fa-solid fa-location-dot"></i></div>
        <div class="flex-1">
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Alamat</p>
            <p class="text-xs font-bold text-gray-800 mt-0.5 leading-relaxed">{{ $supir->alamat ?? '-' }}</p>
        </div>
    </div>

    <div class="pt-2">
        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">Pengaturan Akun</h3>
    </div>

    <button onclick="openPasswordModal()" class="w-full bg-white rounded-xl p-4 shadow-sm border border-gray-50 flex items-center justify-between text-left transition active:bg-gray-50">
        <div class="flex items-center gap-4">
            <div class="text-primary text-base w-5 text-center"><i class="fa-solid fa-gear"></i></div>
            <span class="text-xs font-bold text-gray-700">Pengaturan & Ganti Password</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
    </button>

    <div class="pt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-danger font-bold py-3.5 rounded-xl shadow-sm border border-rose-100 transition active:scale-[0.99] flex items-center justify-center gap-2 text-xs">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar / Log Out
            </button>
        </form>
    </div>
</div>

<div id="password-modal" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/40 backdrop-blur-sm p-0 animate-fade-in">
    <div class="bg-white w-full max-w-md rounded-t-[2rem] shadow-xl border-t border-gray-100 transform transition-all translate-y-full duration-300 flex flex-col max-h-[90%]" id="modal-card">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-gray-800 text-sm"><i class="fa-solid fa-lock text-primary mr-2"></i> Ubah Password Anda</h3>
            <button onclick="closePasswordModal()" class="text-gray-400 p-1 hover:text-gray-600 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <form id="password-form" onsubmit="submitPasswordForm(event)" class="overflow-y-auto p-6 space-y-4 pb-10">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Sekarang</label>
                <input type="password" name="old_password" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs" placeholder="Masukkan password lama">
                <span class="text-[10px] text-danger mt-1 hidden error-field" id="err-old_password"></span>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                <input type="password" name="password" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs" placeholder="Minimal 6 Karakter">
                <span class="text-[10px] text-danger mt-1 hidden error-field" id="err-password"></span>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" required class="input-modern w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs" placeholder="Konfirmasi password baru">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-bold py-3 rounded-xl shadow-md text-xs transition">
                    Simpan Perubahan Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi animasi buka modal pop up gaya mobile dari bawah (Slide Up)
    function openPasswordModal() {
        $('.error-field').addClass('hidden').html('');
        $('#password-form')[0].reset();
        
        $('#password-modal').removeClass('hidden').addClass('flex');
        setTimeout(() => { 
            $('#modal-card').removeClass('translate-y-full').addClass('translate-y-0'); 
        }, 50);
    }

    function closePasswordModal() {
        $('#modal-card').removeClass('translate-y-0').addClass('translate-y-full');
        setTimeout(() => { 
            $('#password-modal').removeClass('flex').addClass('hidden'); 
        }, 200);
    }

    function submitPasswordForm(e) {
        e.preventDefault();
        $('.error-field').addClass('hidden').html('');

        let formData = $('#password-form').serialize();

        $.ajax({
            url: "{{ route('driver.profile.change-password') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    closePasswordModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
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
</script>
@endpush