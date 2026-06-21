<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AKDPSys') | Tiket Bus Online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#1e3a8a', hover: '#1e40af', light: '#dbeafe' },
                        secondary: '#64748b', surface: '#ffffff', background: '#f8fafc',
                        success: '#10b981', danger: '#ef4444', warning: '#f59e0b',
                    },
                    boxShadow: { 'halus': '0 4px 6px -1px rgba(0, 0, 0, 0.05)' }
                }
            }
        }
    </script>

    <style>
        body { background-color: #e2e8f0; color: #0f172a; -webkit-font-smoothing: antialiased; }
        .app-container::-webkit-scrollbar { display: none; }
        .app-container { -ms-overflow-style: none; scrollbar-width: none; }
        #ajax-loader { display: none; z-index: 9999; }
    </style>
    @stack('css')
</head>
<body class="flex items-center justify-center min-h-[100dvh] bg-slate-200 sm:py-8">

    <div id="ajax-loader" class="fixed inset-0 flex items-center justify-center bg-slate-900/20 backdrop-blur-sm z-[9999]">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
    </div>

    <div class="w-full max-w-md bg-background h-[100dvh] sm:h-[800px] sm:rounded-[2.5rem] sm:shadow-2xl relative overflow-hidden flex flex-col app-container sm:border-x-4 sm:border-y-8 sm:border-gray-900 mx-auto">
        
        <main class="flex-1 overflow-y-auto pb-6 app-container relative bg-[#f8fafc]">
            @yield('content')
        </main>

        <nav class="w-full bg-white border-t border-gray-100 sm:rounded-b-[2rem] shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] px-8 pt-3 pb-[calc(0.75rem+env(safe-area-inset-bottom))] flex justify-between items-center z-50 shrink-0">
            <a href="{{ route('customer.home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.home') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="{{ route('customer.tiket.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.tiket.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                <span class="text-[10px] font-medium">Cari</span>
            </a>
            <a href="{{ route('customer.tiket-saya.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.tiket-saya.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                <i class="fa-solid fa-ticket text-xl"></i>
                <span class="text-[10px] font-medium">Tiket Saya</span>
            </a>
            <a href="{{ route('customer.akun.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('customer.akun.*') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                <i class="fa-regular fa-user text-xl"></i>
                <span class="text-[10px] font-medium">Akun</span>
            </a>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $(document).ajaxStart(function() { $('#ajax-loader').fadeIn(150); }).ajaxStop(function() { $('#ajax-loader').fadeOut(150); });
        });
    </script>
    @stack('scripts')
</body>
</html>