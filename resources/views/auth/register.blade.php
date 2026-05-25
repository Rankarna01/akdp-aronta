<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | PT Aronta Citra Persada</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#1e3a8a', surface: '#ffffff', background: '#f8fafc' }
                }
            }
        }
    </script>
    <style>
        .input-modern:focus { outline: none; border-color: #1e3a8a; box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.15); }
    </style>
</head>
<body class="bg-background font-sans antialiased flex items-center justify-center min-h-screen relative overflow-hidden py-10">
    
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="w-full max-w-md bg-surface rounded-2xl shadow-xl p-8 relative z-10 border border-gray-100 mx-4">
        
        <a href="{{ route('landing') }}" class="absolute top-6 left-6 text-gray-400 hover:text-primary transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>

        <div class="text-center mb-8 mt-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                <i class="fa-solid fa-user-plus text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar untuk mulai memesan tiket bus.</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-medium">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-regular fa-user text-gray-400"></i></div>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white" placeholder="Nama sesuai KTP">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-regular fa-envelope text-gray-400"></i></div>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white" placeholder="nama@email.com">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-solid fa-lock text-gray-400"></i></div>
                    <input type="password" name="password" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white" placeholder="Minimal 6 karakter">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Ulangi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-solid fa-check text-gray-400"></i></div>
                    <input type="password" name="password_confirmation" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm transition-all focus:bg-white" placeholder="Ketik ulang password">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-medium py-3 rounded-xl transition-colors shadow-lg shadow-primary/30">
                Daftar Sekarang
            </button>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Masuk di sini</a>
            </p>
        </form>
    </div>
</body>
</html>