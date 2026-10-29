<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIABSEN</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb', // Vivid Royal Blue matching screenshot
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7fc; }
        [x-cloak] { display: none !important; }
        .sidebar-brand-gradient {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
        }
        .custom-card {
            background-color: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 1.25rem;
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 min-h-screen flex antialiased">

    <!-- Left Sidebar Matching Screenshot -->
    <aside class="w-64 bg-brand-600 flex flex-col flex-shrink-0 min-h-screen shadow-xl z-20">
        <!-- Blue Top Section with Graduation Cap -->
        <div class="pt-7 pb-5 px-6 text-center text-white sidebar-brand-gradient">
            <img src="{{ asset('logo.jpeg') }}" alt="Logo Sekolah" class="w-16 h-16 mx-auto mb-2.5 rounded-full object-cover shadow-lg shadow-brand-900/30 ring-2 ring-white/50">
            <h1 class="text-xl font-extrabold tracking-wider text-white">SIABSEN</h1>
            <p class="text-[11px] text-blue-100/90 font-medium tracking-tight mt-0.5">Sistem Informasi Absensi Sekolah</p>
        </div>

        <!-- White Menu Container with Rounded Top Corners -->
        <div class="flex-1 bg-white rounded-t-[28px] pt-6 pb-6 px-4 flex flex-col justify-between shadow-xs">
            <nav class="space-y-1.5 overflow-y-auto">
                @if(auth()->user()->isTU())
                    <!-- TU Menu -->
                    <a href="{{ route('tu.dashboard') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('tu.rekap.harian') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.rekap.harian') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Rekap Harian</span>
                    </a>

                    <a href="{{ route('tu.rekap.bulanan') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.rekap.bulanan') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Rekap Bulanan</span>
                    </a>

                    <a href="{{ route('tu.kelas.index') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.kelas.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Kelola Kelas</span>
                    </a>

                    <a href="{{ route('tu.siswa.index') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.siswa.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Kelola Siswa</span>
                    </a>

                    <a href="{{ route('tu.guru.index') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('tu.guru.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Kelola Guru</span>
                    </a>
                @else
                    <!-- Guru Menu -->
                    <a href="{{ route('guru.dashboard') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('guru.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('guru.absensi.index') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('guru.absensi.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Input Absensi</span>
                    </a>

                    <a href="{{ route('guru.rekap.bulanan') }}"
                        class="flex items-center space-x-3.5 px-4 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('guru.rekap.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'text-slate-600 hover:text-brand-600 hover:bg-blue-50/70' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Rekap Bulanan</span>
                    </a>
                @endif
            </nav>

            <!-- Logout Link Matching Screenshot -->
            <div class="pt-4 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-500 hover:text-rose-600 hover:bg-rose-50 w-full transition">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar Matching Screenshot -->
        <header class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-10">
            <!-- Left: Hamburger & Title -->
            <div class="flex items-center space-x-4">
                <button class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
            </div>

            <!-- Middle: Search Input Pill -->
            <div class="hidden md:flex items-center w-72 lg:w-96">
                <form method="GET" action="{{ auth()->user()->isTU() ? route('tu.siswa.index') : route('guru.dashboard') }}" class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Cari siswa atau kelas..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-full text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </form>
            </div>

            <!-- Right: Notification & Profile -->
            <div class="flex items-center space-x-6">
                <!-- Notification Bell -->
                <button class="relative p-2 rounded-full text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-brand-600 text-white text-[9px] font-bold flex items-center justify-center border-2 border-white">
                        3
                    </span>
                </button>

                <!-- User Profile Pill -->
                <div class="flex items-center space-x-3 pl-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-blue-100">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold text-slate-900 leading-tight">Hello, {{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium leading-tight mt-0.5">
                            @if(auth()->user()->isTU())
                                Tata Usaha &bull; Admin
                            @else
                                Guru &bull; Wali Kelas
                            @endif
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-6 md:p-8 space-y-6 overflow-y-auto">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer Matching Screenshot -->
        <footer class="px-8 py-4 bg-white/70 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400">
            <div>
                &copy; {{ date('Y') }} Sistem Informasi Absensi Siswa. All rights reserved.
            </div>
            <div class="mt-1 sm:mt-0 font-medium">
                Made with <span class="text-rose-500">❤️</span> for School
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
