<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | PT Aronta Citra Persada</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'], // Poppins Global
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#1e3a8a', // Biru Tua
                            hover: '#1e40af',
                            light: '#dbeafe'
                        },
                        secondary: '#64748b', // Modern secondary
                        surface: '#ffffff', // Putih
                        background: '#f8fafc', // Clean background
                        success: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                    },
                    boxShadow: {
                        'halus': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS untuk UI Modern -->
    <style>
        body { 
            background-color: #f8fafc; 
            color: #0f172a; 
            -webkit-font-smoothing: antialiased;
        }
        
        /* Efek fokus input modern */
        .input-modern {
            transition: all 0.3s ease;
        }
        .input-modern:focus { 
            outline: none; 
            border-color: #1e3a8a; 
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.15); 
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Global AJAX Loader Spinner */
        #ajax-loader { display: none; z-index: 9999; }
    </style>
    
    @stack('css')
</head>
<body class="font-sans flex h-screen overflow-hidden bg-background">

    <!-- AJAX Loading Spinner -->
    <div id="ajax-loader" class="fixed inset-0 flex items-center justify-center bg-slate-900/20 backdrop-blur-sm">
        <div class="animate-spin rounded-full h-14 w-14 border-4 border-primary border-t-transparent"></div>
    </div>

    <!-- Sidebar Wrapper (Nanti di-include berdasarkan Role) -->
    @yield('sidebar')

    <!-- Main Wrapper -->
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        
        <!-- Navbar Global -->
        @include('components.navbar')

        <!-- Main Content -->
        <main class="w-full flex-grow p-6 sm:p-8">
            @include('components.breadcrumb')
            
            <!-- Konten Utama Halaman -->
            <div class="mt-4">
                @yield('content')
            </div>
        </main>

        <!-- Footer Global -->
        @include('components.footer')
    </div>

    <!-- Container untuk Modal AJAX (Create/Edit) -->
    <div id="modal-container"></div>

    <!-- Core Scripts Library -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Global AJAX & Security Setup -->
    <script>
        $(document).ready(function() {
            // 1. Setup CSRF Token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 2. Tampilkan/Sembunyikan Loader saat AJAX berjalan
            $(document).ajaxStart(function() {
                $('#ajax-loader').fadeIn(150);
            }).ajaxStop(function() {
                $('#ajax-loader').fadeOut(150);
            });

            // 3. Global Error Handling
            $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                if (jqxhr.status === 422) {
                    // Validasi form error akan di-handle per modul
                    return; 
                }
                if (jqxhr.status === 401 || jqxhr.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi Berakhir',
                        text: 'Sesi Anda telah habis. Silakan muat ulang halaman.',
                        confirmButtonColor: '#1e3a8a'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal terhubung ke server. Silakan coba lagi.',
                        confirmButtonColor: '#1e3a8a'
                    });
                }
            });

            $(document).ready(function() {
        // Script untuk Dropdown Sidebar
        $('.toggle-dropdown').on('click', function(e) {
            e.preventDefault();
            
            let $this = $(this);
            let $submenu = $this.next('.submenu');
            let $icon = $this.find('.chevron-icon');

            // Tutup dropdown lain yang sedang terbuka (Opsional: hapus jika ingin bisa buka banyak sekaligus)
            $('.submenu').not($submenu).slideUp(300);
            $('.chevron-icon').not($icon).removeClass('rotate-180');
            $('.toggle-dropdown').not($this).removeClass('bg-primary/5 text-primary');

            // Toggle dropdown yang diklik
            $submenu.slideToggle(300);
            $icon.toggleClass('rotate-180');
            $this.toggleClass('bg-primary/5 text-primary');
        });
    });
        });
    </script>

    @stack('scripts')
</body>
</html>