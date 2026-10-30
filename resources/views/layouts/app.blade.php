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
                            50: '#E8F6EE',
                            100: '#D1F0DE',
                            500: '#10B981',
                            600: '#0E8345', // Primary brand emerald matching screenshot
                            700: '#0B6E3F',
                            800: '#095A34',
                        },
                        emerald: {
                            50: '#E8F6EE',
                            100: '#D1F0DE',
                            500: '#10B981',
                            600: '#0E8345',
                            700: '#0B6E3F',
                            800: '#095A34',
                            900: '#063B22',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAFBFD;
        }
        [x-cloak] { display: none !important; }
        .oripio-card, .custom-card {
            background-color: #ffffff;
            border: 1px solid #EEF2F6;
            border-radius: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 bg-[#FAFBFD] min-h-screen antialiased">

    <!-- Edge-to-Edge Fullscreen App Layout -->
    <div class="flex flex-col md:flex-row min-h-screen w-full">

        <!-- Left Clean White Sidebar -->
        <aside class="w-full md:w-64 lg:w-72 bg-white border-r border-slate-100 flex flex-col justify-between p-5 lg:p-6 flex-shrink-0 md:min-h-screen md:sticky md:top-0 md:h-screen md:overflow-y-auto">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-100/80">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo Sekolah" class="w-9 h-9 rounded-full object-cover shadow-xs ring-1 ring-slate-200">
                        <div>
                            <span class="block text-base font-extrabold text-slate-900 tracking-tight leading-none">SIABSEN</span>
                            <span class="text-[10px] font-semibold text-slate-400 leading-none mt-0.5 block">Sistem Absensi</span>
                        </div>
                    </div>
                    <button class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-50 transition" title="Toggle Sidebar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Groups -->
                <div class="mt-6 space-y-6">

                    <!-- Section: MAIN MENU -->
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Main Menu</span>
                        <nav class="space-y-1">
                            @if(auth()->user()->isTU())
                                <a href="{{ route('tu.dashboard') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.dashboard') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </div>
                                </a>

                                <a href="{{ route('tu.rekap.harian') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.rekap.harian') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.rekap.harian') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.rekap.harian') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Rekap Harian</span>
                                    </div>
                                </a>

                                <a href="{{ route('tu.rekap.bulanan') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.rekap.bulanan') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.rekap.bulanan') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.rekap.bulanan') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span>Rekap Bulanan</span>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('guru.dashboard') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('guru.dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('guru.dashboard') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('guru.dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </div>
                                </a>

                                <a href="{{ route('guru.absensi.index') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('guru.absensi.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('guru.absensi.*') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('guru.absensi.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Input Absensi</span>
                                    </div>
                                </a>

                                <a href="{{ route('guru.rekap.bulanan') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('guru.rekap.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('guru.rekap.*') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('guru.rekap.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span>Rekap Bulanan</span>
                                    </div>
                                </a>
                            @endif
                        </nav>
                    </div>

                    <!-- Section: FEATURES / MANAJEMEN -->
                    @if(auth()->user()->isTU())
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Manajemen Data</span>
                            <nav class="space-y-1">
                                <a href="{{ route('tu.kelas.index') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.kelas.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.kelas.*') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.kelas.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span>Kelola Kelas</span>
                                    </div>
                                </a>

                                <a href="{{ route('tu.siswa.index') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.siswa.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.siswa.*') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.siswa.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Kelola Siswa</span>
                                    </div>
                                </a>

                                <a href="{{ route('tu.guru.index') }}"
                                   class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ request()->routeIs('tu.guru.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-2 h-2 rounded-full {{ request()->routeIs('tu.guru.*') ? 'bg-emerald-600' : 'bg-transparent' }}"></div>
                                        <svg class="w-4 h-4 {{ request()->routeIs('tu.guru.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Kelola Guru</span>
                                    </div>
                                </a>
                            </nav>
                        </div>
                    @endif

                    <!-- Section: GENERAL -->
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">General</span>
                        <nav class="space-y-1">
                            <a href="javascript:void(0)" onclick="alert('SIABSEN v2.0 - Sistem Informasi Absensi Digital terhubung dengan database MySQL. Dikembangkan khusus untuk akses Tata Usaha & Guru.')"
                               class="group flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition">
                                <div class="w-2 h-2"></div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Informasi Sistem</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="group w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <div class="w-2 h-2"></div>
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Log out</span>
                                </button>
                            </form>
                        </nav>
                    </div>

                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">

            <!-- Top Header Navbar Matching Screenshot -->
            <header class="px-6 lg:px-8 py-4 bg-white border-b border-slate-100 flex items-center justify-between gap-4 sticky top-0 z-20">
                <!-- Search Bar & Realtime Date Pill (Sebelah Search) -->
                <div class="flex items-center space-x-3 flex-1 max-w-xl">
                    <div class="flex-1 max-w-md">
                        <form method="GET" action="{{ auth()->user()->isTU() ? route('tu.siswa.index') : route('guru.dashboard') }}" class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" placeholder="Search..."
                                   class="w-full pl-9 pr-14 py-2 bg-slate-50 border border-slate-200/80 rounded-full text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[10px] font-bold text-slate-400">
                                ⌘ K
                            </span>
                        </form>
                    </div>

                    <!-- Date Pill Badge -->
                    <div class="hidden sm:flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-semibold text-slate-600 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>

                <!-- User Profile (Tanpa Card / Icon Bulat) -->
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 leading-none">
                            {{ auth()->user()->isTU() ? 'Tata Usaha' : 'Guru / Wali Kelas' }}
                        </span>
                    </div>
                </div>
            </header>

            <!-- Page Body Container -->
            <main class="flex-1 p-6 lg:p-8 space-y-6 overflow-y-auto">

                <!-- Flash Notifications -->
                @if(session('success'))
                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-semibold flex items-center space-x-2 shadow-xs">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-800 text-xs font-semibold flex items-center space-x-2 shadow-xs">
                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer matching minimalist aesthetic -->
            <footer class="px-8 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400">
                <div>
                    &copy; {{ date('Y') }} SIABSEN &bull; Sistem Informasi Presensi Siswa
                </div>
                <div class="mt-1 sm:mt-0 font-medium">
                    Platform Presensi Digital &bull; <span class="text-emerald-600 font-semibold">Aktif</span>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
