<header class="bg-surface shadow-halus sticky top-0 z-40 w-full h-16 flex items-center justify-between px-6 transition-all duration-300 rounded-b-xl border-b border-gray-100">
    <!-- Kiri: Mobile Toggle & Title (Optional) -->
    <div class="flex items-center gap-4">
        <button class="text-secondary hover:text-primary transition md:hidden focus:outline-none" id="mobile-sidebar-toggle">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <h2 class="text-lg font-semibold text-gray-800 hidden sm:block">PT Aronta Citra Persada</h2>
    </div>

    <!-- Kanan: Notifikasi & Profile -->
    <div class="flex items-center gap-5">
        <!-- Notifikasi -->
        <button class="relative text-secondary hover:text-primary transition focus:outline-none">
            <i class="fa-regular fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-danger text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">3</span>
        </button>

        <!-- Divider -->
        <div class="h-8 w-px bg-gray-200"></div>

        <!-- Profile Dropdown -->
        <div class="relative group cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-gray-800">Nama User</p>
                    <p class="text-xs text-secondary">Role User</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                    U
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-secondary"></i>
            </div>
            
            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-surface rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right">
                <div class="py-2">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition"><i class="fa-regular fa-user mr-2"></i> Profil Saya</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition"><i class="fa-solid fa-gear mr-2"></i> Pengaturan</a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <!-- Form Logout Dummy -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
    @csrf
    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-danger hover:bg-danger/10 transition cursor-pointer">
        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
    </button>
</form>
                </div>
            </div>
        </div>
    </div>
</header>