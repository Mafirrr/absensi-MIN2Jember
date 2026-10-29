<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen flex antialiased selection:bg-stone-200">

    <!-- Minimalist Sidebar -->
    <aside class="w-60 bg-white border-r border-stone-200 flex flex-col flex-shrink-0 min-h-screen">
        <!-- Brand -->
        <div class="h-16 flex items-center px-6 border-b border-stone-200">
            <a href="{{ auth()->user()->isTU() ? route('tu.dashboard') : route('guru.dashboard') }}" class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded bg-stone-900 text-white flex items-center justify-center font-bold text-xs">
                    A
                </div>
                <span class="text-sm font-semibold tracking-tight text-stone-900">Sistem Absensi</span>
            </a>
        </div>

        <!-- User Profile Minimal Card -->
        <div class="px-5 py-3.5 border-b border-stone-100 bg-stone-50/50">
            <p class="text-xs font-semibold text-stone-900 truncate">{{ auth()->user()->name }}</p>
            <div class="mt-1">
                @if(auth()->user()->isTU())
                    <span class="inline-block text-[10px] font-medium text-stone-600 bg-stone-200/70 px-2 py-0.5 rounded">
                        Tata Usaha
                    </span>
                @else
                    <span class="inline-block text-[10px] font-medium text-stone-600 bg-stone-200/70 px-2 py-0.5 rounded">
                        Guru / Wali Kelas
                    </span>
                @endif
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 text-xs font-medium overflow-y-auto">
            @if(auth()->user()->isTU())
                <div class="px-3 pb-1 text-[10px] font-semibold tracking-wider text-stone-400 uppercase">Menu Utama</div>
                
                <a href="{{ route('tu.dashboard') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.dashboard') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard TU</span>
                </a>

                <div class="pt-4 px-3 pb-1 text-[10px] font-semibold tracking-wider text-stone-400 uppercase">Rekapitulasi</div>

                <a href="{{ route('tu.rekap.harian') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.rekap.harian') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Rekap Harian</span>
                </a>

                <a href="{{ route('tu.rekap.bulanan') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.rekap.bulanan') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Rekap Bulanan</span>
                </a>

                <div class="pt-4 px-3 pb-1 text-[10px] font-semibold tracking-wider text-stone-400 uppercase">Kelola Data</div>

                <a href="{{ route('tu.kelas.index') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.kelas.*') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Kelola Kelas</span>
                </a>

                <a href="{{ route('tu.siswa.index') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.siswa.*') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Kelola Siswa</span>
                </a>

                <a href="{{ route('tu.guru.index') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('tu.guru.*') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Kelola Guru</span>
                </a>
            @else
                <div class="px-3 pb-1 text-[10px] font-semibold tracking-wider text-stone-400 uppercase">Menu Guru</div>

                <a href="{{ route('guru.dashboard') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('guru.dashboard') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('guru.absensi.index') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('guru.absensi.*') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Input Absensi</span>
                </a>

                <a href="{{ route('guru.rekap.bulanan') }}"
                    class="flex items-center space-x-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('guru.rekap.*') ? 'bg-stone-900 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Rekap Bulanan</span>
                </a>
            @endif
        </nav>

        <!-- Logout Area -->
        <div class="p-3 border-t border-stone-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 text-xs font-medium text-stone-600 hover:text-rose-600 hover:bg-stone-100 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Minimal Top Header -->
        <header class="h-16 bg-white border-b border-stone-200 px-8 flex items-center justify-between sticky top-0 z-20">
            <div>
                <h1 class="text-sm font-semibold text-stone-900">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="text-xs text-stone-500 font-medium">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </header>

        <!-- Content Body -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-5 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center">
                    <svg class="w-4 h-4 text-emerald-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center">
                    <svg class="w-4 h-4 text-rose-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
