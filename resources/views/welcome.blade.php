<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Transportasi AKDP | PT Aronta Citra Persada</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#1e3a8a', secondary: '#64748b', surface: '#ffffff', background: '#f8fafc', success: '#10b981' }
                }
            }
        }
    </script>
    <style>
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .hero-pattern { background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: flex;
            width: max-content;
            animation: marquee 25s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-background font-sans antialiased text-gray-800">

    <nav class="fixed w-full z-50 glass-nav border-b border-gray-100 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center text-xl shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-bus-simple"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-primary">AKDPSys<span class="text-gray-800">.</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="nav-link text-sm font-semibold text-primary">Beranda</a>
                    <a href="#rute" class="nav-link text-sm font-semibold text-secondary hover:text-primary transition">Rute</a>
                    <a href="#jadwal" class="nav-link text-sm font-semibold text-secondary hover:text-primary transition">Jadwal</a>
                    <a href="#armada" class="nav-link text-sm font-semibold text-secondary hover:text-primary transition">Armada</a>
                    <a href="#tentang" class="nav-link text-sm font-semibold text-secondary hover:text-primary transition">Tentang Kami</a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard.redirect') }}" class="text-sm font-bold text-primary hover:text-blue-900 transition border border-primary/20 bg-primary/5 px-5 py-2.5 rounded-xl">Masuk Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-secondary hover:text-primary transition">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-primary text-white px-6 py-2.5 rounded-xl shadow-lg shadow-primary/30 hover:bg-blue-900 transition active:scale-95">Daftar Akun</a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-500 hover:text-primary focus:outline-none text-2xl">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#beranda" class="block px-3 py-3 rounded-xl text-base font-medium text-primary bg-blue-50">Beranda</a>
                <a href="#rute" class="block px-3 py-3 rounded-xl text-base font-medium text-gray-700 hover:bg-gray-50">Rute</a>
                <a href="#jadwal" class="block px-3 py-3 rounded-xl text-base font-medium text-gray-700 hover:bg-gray-50">Jadwal</a>
                <a href="#armada" class="block px-3 py-3 rounded-xl text-base font-medium text-gray-700 hover:bg-gray-50">Armada</a>
                <a href="#tentang" class="block px-3 py-3 rounded-xl text-base font-medium text-gray-700 hover:bg-gray-50">Tentang Kami</a>
                <div class="h-px w-full bg-gray-100 my-4"></div>
                @auth
                    <a href="{{ route('dashboard.redirect') }}" class="block text-center px-3 py-3 rounded-xl text-base font-bold bg-primary text-white">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block text-center px-3 py-3 rounded-xl text-base font-bold border border-gray-200 text-gray-700 mb-2">Masuk</a>
                    <a href="{{ route('register') }}" class="block text-center px-3 py-3 rounded-xl text-base font-bold bg-primary text-white shadow-md">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </nav>

    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-primary hero-pattern">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-white opacity-5 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="bg-white/10 text-blue-100 text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 uppercase tracking-widest inline-block mb-6">Transportasi AKDP Terbaik</span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Perjalanan Nyaman, <br> Sampai Tujuan.
                </h1>
                <p class="text-lg text-blue-100 mb-10 leading-relaxed font-light">
                    Pesan tiket bus Antar Kota Dalam Provinsi (AKDP) dengan mudah, cepat, dan aman melalui sistem kami. Nikmati fasilitas armada terbaik dari PT Aronta Citra Persada.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-primary font-bold rounded-2xl shadow-xl hover:bg-gray-50 transition active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-ticket"></i> Pesan Tiket Sekarang
                    </a>
                    <a href="#jadwal" class="w-full sm:w-auto px-8 py-4 bg-primary text-white border border-white/30 font-bold rounded-2xl hover:bg-white/10 transition active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-regular fa-calendar"></i> Lihat Jadwal
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="rute" class="py-24 bg-background overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Rute Perjalanan Populer</h2>
                <p class="text-secondary max-w-2xl mx-auto">Kami melayani berbagai rute strategis antar kota dalam provinsi untuk mendukung mobilitas Anda sehari-hari.</p>
            </div>
        </div>

        <div class="relative w-full overflow-hidden pb-4">
            <!-- Blur overlay di pinggir kiri dan kanan agar animasi terlihat halus -->
            <div class="absolute left-0 top-0 w-16 h-full bg-gradient-to-r from-background to-transparent z-10"></div>
            <div class="absolute right-0 top-0 w-16 h-full bg-gradient-to-l from-background to-transparent z-10"></div>
            
            <div class="animate-marquee gap-4 px-4">
                <!-- Kita melooping 2 kali agar saat scroll selesai, elemen selanjutnya sudah siap menyambung -->
                @for($i = 0; $i < 2; $i++)
                    @forelse($rutePopuler as $rute)
                        <div class="bg-white rounded-2xl px-6 py-5 shadow-sm border border-gray-100 flex items-center justify-between min-w-[320px] hover:shadow-md hover:border-primary/30 transition group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-primary rounded-xl flex items-center justify-center text-sm group-hover:bg-primary group-hover:text-white transition">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Rute</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-800 text-sm whitespace-nowrap">{{ $rute->kota_asal }}</span>
                                        <i class="fa-solid fa-arrow-right text-gray-300 text-[10px] group-hover:text-primary transition"></i>
                                        <span class="font-bold text-gray-800 text-sm whitespace-nowrap">{{ $rute->kota_tujuan }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-px h-8 bg-gray-100 mx-4"></div>
                                <a href="{{ route('register') }}" class="text-xs font-bold text-primary hover:text-blue-900 whitespace-nowrap transition">Cek Tiket &rarr;</a>
                            </div>
                        </div>
                    @empty
                        @if($i == 0)
                            <div class="w-full text-center py-4 text-gray-500 text-sm">
                                Belum ada rute aktif yang tersedia.
                            </div>
                        @endif
                    @endforelse
                @endfor
            </div>
        </div>
    </section>

    <section id="jadwal" class="py-24 bg-white relative border-t border-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3 tracking-tight">Jadwal Keberangkatan Terdekat</h2>
                    <p class="text-secondary text-sm md:text-base max-w-2xl">Jangan sampai kehabisan, pesan kursi Anda di jadwal terdekat hari ini.</p>
                </div>
                <a href="{{ route('register') }}" class="bg-primary/5 hover:bg-primary/10 text-primary border border-primary/20 font-bold px-6 py-3 rounded-xl transition flex items-center justify-center gap-2 whitespace-nowrap text-sm">
                    Lihat Semua Jadwal <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($jadwalTerdekat as $jadwal)
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 relative group flex flex-col justify-between h-full">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary to-blue-400"></div>
                        
                        <div>
                            <div class="flex justify-between items-start mb-6 pt-2">
                                <div>
                                    <p class="text-2xl font-black text-gray-900 tracking-tight">{{ substr($jadwal->waktu_berangkat, 0, 5) }} <span class="text-sm font-bold text-gray-400">WIB</span></p>
                                    <p class="text-[11px] text-gray-500 font-medium uppercase tracking-wider mt-1"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</p>
                                </div>
                                <span class="bg-success/10 text-success text-[10px] font-bold px-3 py-1 rounded-md uppercase border border-success/20">Tersedia</span>
                            </div>

                            <div class="flex items-center gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="w-12 h-12 bg-white text-primary rounded-xl shadow-sm flex items-center justify-center border border-gray-100">
                                    <i class="fa-solid fa-bus text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-gray-800">{{ $jadwal->rute->kota_asal }} <i class="fa-solid fa-arrow-right text-[10px] text-gray-300 mx-1"></i> {{ $jadwal->rute->kota_tujuan }}</h4>
                                    <p class="text-xs font-semibold text-primary mt-1">{{ $jadwal->armada->nama_bus }} <span class="text-gray-400 font-normal">| {{ $jadwal->armada->tipe_bus }}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Harga Mulai</p>
                                <p class="text-xl font-black text-gray-800">Rp {{ number_format($jadwal->harga_tiket, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('register') }}" class="bg-primary text-white font-bold px-6 py-2.5 rounded-xl text-sm hover:bg-blue-900 transition shadow-md hover:shadow-lg flex items-center gap-2">Pesan <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada jadwal keberangkatan untuk hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="armada" class="py-24 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Armada Eksklusif Kami</h2>
                <p class="text-secondary max-w-2xl mx-auto">Perjalanan Anda didukung oleh armada bus terbaru, terawat, dan dilengkapi fasilitas standar kenyamanan tinggi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($armadaBus as $armada)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition group">
                        <div class="h-40 bg-gray-100 relative flex items-center justify-center overflow-hidden">
                            @if($armada->gambar)
                                <img src="{{ asset('storage/' . $armada->gambar) }}" alt="{{ $armada->nama_bus }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <i class="fa-solid fa-bus text-6xl text-gray-300 group-hover:scale-110 transition-transform duration-500"></i>
                            @endif
                            <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $armada->nama_bus }}</h3>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-mono font-bold px-2 py-1 rounded">{{ $armada->plat_nomor }}</span>
                                <span class="bg-blue-50 text-primary text-[10px] font-bold px-2 py-1 rounded">{{ $armada->tipe_bus }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-4">
                                <span class="text-gray-500 font-medium"><i class="fa-solid fa-chair text-gray-400 mr-1"></i> Kapasitas</span>
                                <span class="font-bold text-gray-800">{{ $armada->total_kursi }} Seat</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-white rounded-2xl border border-gray-100">
                        <p class="text-gray-500">Belum ada data armada.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="tentang" class="bg-gray-900 text-white pt-20 pb-10 border-t-8 border-primary relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full mix-blend-screen filter blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center text-xl">
                            <i class="fa-solid fa-bus-simple"></i>
                        </div>
                        <span class="font-bold text-2xl tracking-tight">AKDPSys<span class="text-blue-400">.</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        PT Aronta Citra Persada adalah perusahaan penyedia jasa transportasi darat Antar Kota Dalam Provinsi (AKDP) terpercaya di Sumatera Utara yang mengutamakan keamanan dan kenyamanan penumpang.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-6 text-white">Menu Navigasi</h3>
                    <ul class="space-y-4">
                        <li><a href="#beranda" class="text-gray-400 hover:text-white transition text-sm flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-primary"></i> Beranda</a></li>
                        <li><a href="#rute" class="text-gray-400 hover:text-white transition text-sm flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-primary"></i> Cek Rute</a></li>
                        <li><a href="#jadwal" class="text-gray-400 hover:text-white transition text-sm flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-primary"></i> Jadwal Bus</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition text-sm flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-primary"></i> Login Member</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-6 text-white">Hubungi Kami</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <i class="fa-solid fa-location-dot text-primary mt-1"></i>
                            <span class="text-gray-400 text-sm">Jl. Sisingamangaraja No. 123, Medan, Sumatera Utara, Indonesia.</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-phone text-primary"></i>
                            <span class="text-gray-400 text-sm">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fa-solid fa-envelope text-primary"></i>
                            <span class="text-gray-400 text-sm">cs@aronta.co.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">&copy; 2026 PT Aronta Citra Persada. All rights reserved.</p>
                <p class="text-sm text-gray-500 flex items-center gap-1">Built with <i class="fa-solid fa-heart text-red-500"></i> using Laravel.</p>
            </div>
        </div>
    </section>

    <script>
        // Toggle Mobile Menu
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        // Navbar Scroll Effect (Spy Scroll & BG ubah)
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }

            // Spy Scroll Logic (Menyorot menu aktif saat scroll)
            let current = '';
            const sections = document.querySelectorAll('section');
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (scrollY >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('text-primary');
                link.classList.add('text-secondary');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('text-primary');
                    link.classList.remove('text-secondary');
                }
            });
        });
    </script>
</body>
</html>