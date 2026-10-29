<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Absensi</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-sm">
        
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-stone-900 text-stone-100 mb-3 shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-stone-900 tracking-tight">Sistem Absensi</h1>
            <p class="text-xs text-stone-500 mt-1">Portal masuk Tata Usaha & Guru</p>
        </div>

        <!-- Minimalist Form Card -->
        <div class="bg-white rounded-xl border border-stone-200 p-6 shadow-xs">
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-stone-700 mb-1.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@sekolah.sch.id"
                        class="w-full px-3 py-2 bg-white border border-stone-300 rounded-lg text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-medium text-stone-700">Kata Sandi</label>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                        class="w-full px-3 py-2 bg-white border border-stone-300 rounded-lg text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-stone-900 focus:ring-1 focus:ring-stone-900 transition">
                </div>

                <div class="flex items-center pt-1">
                    <label class="flex items-center text-xs text-stone-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-stone-300 text-stone-900 focus:ring-stone-900">
                        <span class="ml-2">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium tracking-wide transition shadow-xs">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-stone-400 mt-8">
            &copy; {{ date('Y') }} Sistem Absensi Sekolah. Hak cipta dilindungi.
        </p>

    </div>

</body>
</html>
