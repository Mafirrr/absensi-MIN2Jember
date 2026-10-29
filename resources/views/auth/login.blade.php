<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi Sekolah (SIABSEN)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-4xl grid md:grid-cols-5 bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        
        <!-- Left Side: Brand & Hero Info -->
        <div class="md:col-span-2 bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-800 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-blue-400/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center mb-6 shadow-inner border border-white/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold tracking-tight">SIABSEN</h2>
                <p class="text-blue-100 text-sm mt-1">Sistem Presensi & Kehadiran Siswa Digital</p>

                <div class="mt-8 space-y-4 text-xs text-blue-100/90 leading-relaxed">
                    <div class="flex items-start space-x-3">
                        <div class="w-5 h-5 rounded-full bg-blue-500/30 flex items-center justify-center text-white mt-0.5 flex-shrink-0">✓</div>
                        <p><strong class="text-white">Tata Usaha (TU):</strong> Mengakses semua kelas, rekap harian & bulanan, kelola data kelas & siswa.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/30 flex items-center justify-center text-white mt-0.5 flex-shrink-0">✓</div>
                        <p><strong class="text-white">Guru / Wali Kelas:</strong> Input kehadiran harian dan rekap bulanan khusus kelas yang diajar.</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-8 border-t border-white/15 mt-8">
                <p class="text-[11px] text-blue-200">© 2026 SIABSEN. Akses terbatas untuk staf sekolah yang berwenang.</p>
            </div>
        </div>

        <!-- Right Side: Login Form & Quick Demos -->
        <div class="md:col-span-3 p-8 sm:p-10 flex flex-col justify-center bg-slate-50/50">
            <div>
                <h3 class="text-2xl font-bold text-slate-800">Masuk ke Sistem</h3>
                <p class="text-sm text-slate-500 mt-1">Gunakan akun Tata Usaha atau Guru Anda</p>
            </div>

            @if(session('success'))
                <div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4" id="loginForm">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Sekolah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', 'tu@sekolah.sch.id') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" value="password123" required
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center text-xs text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span class="ml-2">Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md shadow-blue-500/25 transition">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Selector -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3">Akun Demo (Klik untuk Isi Otomatis)</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- TU Demo -->
                    <button type="button" onclick="selectDemo('tu@sekolah.sch.id', 'password123')"
                        class="text-left p-3 rounded-xl border border-purple-200 bg-purple-50/70 hover:bg-purple-100/80 transition flex items-center space-x-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                            TU
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-purple-950 truncate">Tata Usaha (TU)</p>
                            <p class="text-[11px] text-purple-700 truncate">tu@sekolah.sch.id</p>
                        </div>
                    </button>

                    <!-- Guru Demo 1 -->
                    <button type="button" onclick="selectDemo('guru@sekolah.sch.id', 'password123')"
                        class="text-left p-3 rounded-xl border border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100/80 transition flex items-center space-x-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                            GR
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-emerald-950 truncate">Guru (X RPL 1)</p>
                            <p class="text-[11px] text-emerald-700 truncate">guru@sekolah.sch.id</p>
                        </div>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function selectDemo(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
