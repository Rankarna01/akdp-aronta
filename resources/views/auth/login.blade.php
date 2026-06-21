<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PT Aronta Citra Persada</title>
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
<body class="bg-background font-sans antialiased flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="w-full max-w-md bg-surface rounded-2xl shadow-xl p-8 relative z-10 border border-gray-100 mx-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                <i class="fa-solid fa-bus text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
            <p class="text-sm text-gray-500 mt-1">Sistem Transportasi AKDP PT Aronta</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200' }} rounded-xl text-sm transition-all focus:bg-white" placeholder="nama@email.com">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" required class="input-modern w-full pl-10 pr-4 py-2.5 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200' }} rounded-xl text-sm transition-all focus:bg-white" placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2">
                <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">Ingat Saya</label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-blue-900 text-white font-medium py-2.5 rounded-xl transition-colors shadow-lg shadow-primary/30 flex justify-center items-center gap-2 mb-4">
                Masuk ke Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>

            <div class="text-center mt-6 border-t border-gray-100 pt-4">
                <p class="text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>