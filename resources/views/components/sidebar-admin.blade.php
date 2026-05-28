<aside class="w-64 bg-surface h-screen shadow-halus flex flex-col transition-transform duration-300 flex-shrink-0 border-r border-gray-100 fixed md:relative z-50 transform -translate-x-full md:translate-x-0" id="sidebar">
    <div class="h-16 flex items-center justify-center border-b border-gray-100 px-4">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-bus text-2xl text-primary"></i>
            <span class="text-xl font-bold text-gray-800 tracking-wide">AKDP<span class="text-primary">Sys</span></span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-2 custom-scrollbar">
        
        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Utama</p>
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition mb-4 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center"></i>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        @php $isMasterDataActive = request()->routeIs('admin.armada.*', 'admin.supir.*', 'admin.rute.*', 'admin.kursi.*', 'admin.metode-pembayaran-master.*'); @endphp
        <div class="menu-group">
            <button class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition group toggle-dropdown {{ $isMasterDataActive ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-database w-5 text-center transition {{ $isMasterDataActive ? 'scale-110' : 'group-hover:scale-110' }}"></i>
                    <span class="font-medium text-sm">Master Data</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 chevron-icon {{ $isMasterDataActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isMasterDataActive ? 'flex' : 'hidden' }} flex-col pl-9 pr-2 py-1 space-y-1 submenu">
                <a href="{{ route('admin.armada.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.armada.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-bus-simple w-4 text-center"></i> Data Armada
                </a>
                <a href="{{ route('admin.supir.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.supir.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-id-card w-4 text-center"></i> Data Supir
                </a>
                <a href="{{ route('admin.rute.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.rute.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-map-location-dot w-4 text-center"></i> Data Rute
                </a>
                <a href="{{ route('admin.kursi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.kursi.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-chair w-4 text-center"></i> Data Kursi
                </a>
                <a href="{{ route('admin.metode-pembayaran-master.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.metode-pembayaran-master.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-money-check-dollar w-4 text-center"></i> Metode Pembayaran
                </a>
            </div>
        </div>

        @php $isOperasionalActive = request()->routeIs('admin.jadwal.*', 'admin.monitoring.*', 'admin.penumpang.*'); @endphp
        <div class="menu-group">
            <button class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition group toggle-dropdown {{ $isOperasionalActive ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-briefcase w-5 text-center transition {{ $isOperasionalActive ? 'scale-110' : 'group-hover:scale-110' }}"></i>
                    <span class="font-medium text-sm">Operasional</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 chevron-icon {{ $isOperasionalActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isOperasionalActive ? 'flex' : 'hidden' }} flex-col pl-9 pr-2 py-1 space-y-1 submenu">
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.jadwal.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-regular fa-calendar-check w-4 text-center"></i> Jadwal Berangkat
                </a>
                <a href="{{ route('admin.monitoring.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.monitoring.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-location-crosshairs w-4 text-center"></i> Monitoring Perjalanan
                </a>
                <a href="{{ route('admin.penumpang.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.penumpang.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-users w-4 text-center"></i> Data Penumpang
                </a>
            </div>
        </div>

        @php $isTransaksiActive = request()->routeIs('admin.tiket.*', 'admin.pembayaran.*', 'admin.tiket-digital.*'); @endphp
        <div class="menu-group">
            <button class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition group toggle-dropdown {{ $isTransaksiActive ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-ticket-simple w-5 text-center transition {{ $isTransaksiActive ? 'scale-110' : 'group-hover:scale-110' }}"></i>
                    <span class="font-medium text-sm">Transaksi & Tiket</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 chevron-icon {{ $isTransaksiActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isTransaksiActive ? 'flex' : 'hidden' }} flex-col pl-9 pr-2 py-1 space-y-1 submenu">
                <a href="{{ route('admin.tiket.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.tiket.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-cart-arrow-down w-4 text-center"></i> Pemesanan Tiket
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.pembayaran.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-wallet w-4 text-center"></i> Pembayaran
                </a>
                <a href="{{ route('admin.tiket-digital.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.tiket-digital.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-qrcode w-4 text-center"></i> Tiket Digital
                </a>
            </div>
        </div>

        @php $isLaporanActive = request()->routeIs('admin.laporan.*', 'admin.log-aktivitas.*'); @endphp
        <div class="menu-group">
            <button class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition group toggle-dropdown {{ $isLaporanActive ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-chart-line w-5 text-center transition {{ $isLaporanActive ? 'scale-110' : 'group-hover:scale-110' }}"></i>
                    <span class="font-medium text-sm">Laporan & Sistem</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 chevron-icon {{ $isLaporanActive ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isLaporanActive ? 'flex' : 'hidden' }} flex-col pl-9 pr-2 py-1 space-y-1 submenu">
                <a href="{{ route('admin.laporan.perjalanan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.laporan.perjalanan') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i> Laporan Perjalanan
                </a>
                <a href="{{ route('admin.laporan.transaksi') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.laporan.transaksi') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i> Laporan Transaksi
                </a>
                <a href="{{ route('admin.laporan.penumpang') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-sm {{ request()->routeIs('admin.laporan.penumpang') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-clipboard-user w-4 text-center"></i> Laporan Penumpang
                </a>
                <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg text-secondary hover:bg-primary/5 hover:text-primary transition text-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell w-4 text-center"></i> Notifikasi
                    </div>
                    <span class="bg-danger text-white text-[10px] px-2 py-0.5 rounded-full">New</span>
                </a>
                <a href="{{ route('admin.log-aktivitas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition text-sm {{ request()->routeIs('admin.log-aktivitas.*') ? 'text-primary font-bold bg-primary/10' : 'text-secondary hover:bg-primary/5 hover:text-primary' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Log Aktivitas
                </a>
            </div>
        </div>

    </div>
</aside>