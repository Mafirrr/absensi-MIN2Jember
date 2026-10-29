@extends('layouts.app')

@section('title', 'Dashboard TU')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Greeting Row Matching Screenshot -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Welcome back {{ auth()->user()->name }}
            </h2>
            <p class="text-xs text-slate-400 font-medium mt-1">
                Monitor and control student attendance records today for school operational excellence.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Date Pill Badge -->
            <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-semibold text-slate-600">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ now()->translatedFormat('D, d F Y') }}</span>
            </div>

            <!-- Export / Action Button -->
            <a href="{{ route('tu.rekap.harian') }}"
               class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#1E293B] hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Rekap Harian</span>
            </a>
        </div>
    </div>

    <!-- 3 Top Stat Cards Row Matching Screenshot -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- Card 1: Account Balance Style (Hero Stat) -->
        <div class="oripio-card p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Total Siswa Terdaftar</span>
                    </div>

                    <span class="inline-flex items-center text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-200/80 px-2 py-0.5 rounded-lg">
                        {{ $totalKelas }} Rombel ▾
                    </span>
                </div>

                <!-- Big Metric -->
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $totalSiswa }} Siswa
                    </div>
                    <div class="mt-1 flex items-center space-x-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                            +{{ $persenKehadiran }}% ↑
                        </span>
                        <span class="text-[11px] font-medium text-slate-400">tingkat kehadiran hari ini</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Row (Send Money / Request Money style) -->
            <div class="mt-5 grid grid-cols-2 gap-2.5 pt-1">
                <a href="{{ route('tu.rekap.harian') }}"
                   class="inline-flex items-center justify-center space-x-1.5 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    <span>Rekap Harian</span>
                </a>

                <a href="{{ route('tu.rekap.bulanan') }}"
                   class="inline-flex items-center justify-center space-x-1.5 py-2.5 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <span>Rekap Bulanan</span>
                </a>
            </div>
        </div>

        <!-- Card 2: Total Expenses Style -->
        <div class="oripio-card p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Siswa Hadir Hari Ini</span>
                    </div>

                    <button class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="4" r="1.5" />
                            <circle cx="10" cy="10" r="1.5" />
                            <circle cx="10" cy="16" r="1.5" />
                        </svg>
                    </button>
                </div>

                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $hadirHariIni }} Siswa
                    </div>
                    <div class="mt-1 flex items-center space-x-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                            {{ $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100, 1) : 0 }}%
                        </span>
                        <span class="text-[11px] font-medium text-slate-400">dari total seluruh siswa</span>
                    </div>
                </div>
            </div>

            <!-- Sub Progress Bar -->
            <div class="mt-5 pt-1">
                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 mb-1.5">
                    <span>Progress Kehadiran</span>
                    <span class="text-slate-700">{{ $hadirHariIni }} / {{ $totalSiswa }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Savings Style -->
        <div class="oripio-card p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Izin, Sakit & Alpa</span>
                    </div>

                    <button class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="4" r="1.5" />
                            <circle cx="10" cy="10" r="1.5" />
                            <circle cx="10" cy="16" r="1.5" />
                        </svg>
                    </button>
                </div>

                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $izinHariIni + $sakitHariIni + $alpaHariIni }} Siswa
                    </div>
                    <div class="mt-1 flex items-center space-x-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">
                            {{ $alpaHariIni }} Alpa
                        </span>
                        <span class="text-[11px] font-medium text-slate-400">perlu tindak lanjut wali kelas</span>
                    </div>
                </div>
            </div>

            <!-- Mini Pills Breakdown -->
            <div class="mt-5 grid grid-cols-3 gap-2 pt-1 text-center">
                <div class="p-2 rounded-xl bg-blue-50/70 border border-blue-100">
                    <span class="block text-[10px] font-bold text-blue-700 uppercase">Izin</span>
                    <span class="text-sm font-extrabold text-blue-950">{{ $izinHariIni }}</span>
                </div>
                <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                    <span class="block text-[10px] font-bold text-amber-700 uppercase">Sakit</span>
                    <span class="text-sm font-extrabold text-amber-950">{{ $sakitHariIni }}</span>
                </div>
                <div class="p-2 rounded-xl bg-rose-50/70 border border-rose-100">
                    <span class="block text-[10px] font-bold text-rose-700 uppercase">Alpa</span>
                    <span class="text-sm font-extrabold text-rose-950">{{ $alpaHariIni }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Middle Section: Left Widgets (40%) & Right Widgets (60%) Matching Screenshot -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- LEFT COLUMN (5 of 12 cols): My Wallet & My Savings Plan Style -->
        <div class="lg:col-span-5 space-y-5">

            <!-- Widget 1: My Wallet Style -> Daftar Rombel Kelas -->
            <div class="oripio-card p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100/80">
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight">Rombel Kelas Binaan</h3>
                    <a href="{{ route('tu.kelas.index') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700">
                        + Kelola Kelas
                    </a>
                </div>

                <!-- 2x2 Mini Cards Grid Matching Screenshot Currencies -->
                <div class="grid grid-cols-2 gap-3 mt-4">
                    @forelse($kelasList->take(4) as $k)
                        <div class="p-3 rounded-2xl border border-slate-150 bg-slate-50/50 hover:bg-white hover:shadow-xs transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-[10px] font-extrabold flex items-center justify-center">
                                        {{ substr($k['nama_kelas'], 0, 1) }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-800">{{ $k['nama_kelas'] }}</span>
                                </div>
                                <span class="text-slate-300 text-xs">&vellip;</span>
                            </div>

                            <div class="text-sm font-extrabold text-slate-900">
                                {{ $k['total_siswa'] }} Siswa
                            </div>
                            <div class="mt-1 flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 truncate max-w-[80px]">{{ $k['wali_kelas'] }}</span>
                                <span class="font-bold {{ $k['sudah_input'] ? 'text-emerald-600' : 'text-slate-400' }}">
                                    ● {{ $k['sudah_input'] ? 'Diisi (' . $k['persen'] . '%)' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-4 text-center text-xs text-slate-400">
                            Belum ada data rombel kelas.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Widget 2: My Savings Plan Style -> Statistik Kehadiran per Status -->
            <div class="oripio-card p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100/80">
                    <div class="flex items-center space-x-2">
                        <span class="text-slate-400">🎯</span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Statistik Status Kehadiran</h3>
                    </div>
                    <button class="text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="4" r="1.5" />
                            <circle cx="10" cy="10" r="1.5" />
                            <circle cx="10" cy="16" r="1.5" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <!-- Item 1: Tingkat Kehadiran (Hadir) -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <span class="font-bold text-slate-800">Tingkat Hadir</span>
                            </div>
                            <span class="font-extrabold text-emerald-700">{{ $persenKehadiran }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                            <span>{{ $hadirHariIni }} dari {{ $totalSiswa }} siswa hadir</span>
                            <span>Target 95%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ min(100, $persenKehadiran) }}%"></div>
                        </div>
                    </div>

                    <!-- Item 2: Izin & Sakit -->
                    @php
                        $persenIzinSakit = $totalSiswa > 0 ? round((($izinHariIni + $sakitHariIni) / $totalSiswa) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span class="font-bold text-slate-800">Izin & Sakit</span>
                            </div>
                            <span class="font-extrabold text-amber-600">{{ $persenIzinSakit }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                            <span>{{ $izinHariIni + $sakitHariIni }} siswa terkonfirmasi</span>
                            <span>Maks 5%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-amber-400 h-2 rounded-full" style="width: {{ min(100, $persenIzinSakit) }}%"></div>
                        </div>
                    </div>

                    <!-- Item 3: Alpa -->
                    @php
                        $persenAlpa = $totalSiswa > 0 ? round(($alpaHariIni / $totalSiswa) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span class="font-bold text-slate-800">Alpa / Tanpa Keterangan</span>
                            </div>
                            <span class="font-extrabold text-rose-600">{{ $persenAlpa }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                            <span>{{ $alpaHariIni }} siswa belum konfirmasi</span>
                            <span>Target 0%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: {{ min(100, $persenAlpa) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN (7 of 12 cols): Overview Kehadiran -->
        <div class="lg:col-span-7">
            <div class="oripio-card p-5 h-full flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100/80">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-3.5 bg-emerald-600 rounded-xs"></div>
                            <h3 class="text-xs font-bold text-slate-900 tracking-tight">Overview Kehadiran Sekolah</h3>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                <span class="text-[11px]">Kehadiran</span>
                            </div>

                            <span class="text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200/80 px-2.5 py-1 rounded-lg">
                                7 Hari Terakhir ▾
                            </span>

                            <button class="text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <circle cx="10" cy="4" r="1.5" />
                                    <circle cx="10" cy="10" r="1.5" />
                                    <circle cx="10" cy="16" r="1.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Custom Bar Chart Container matching screenshot's rounded pastel pillars with dark active bar -->
                    <div class="pt-8 pb-4">
                        <div class="h-56 flex items-end justify-between gap-3 px-3">
                            @php
                                $maxHadir = max(1, ...$trendHadir);
                            @endphp
                            @foreach($dates as $idx => $dateLabel)
                                @php
                                    $val = $trendHadir[$idx] ?? 0;
                                    $heightPct = round(($val / $maxHadir) * 85);
                                    $isToday = $idx === (count($dates) - 1);
                                @endphp
                                <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                                    @if($isToday)
                                        <div class="absolute -top-7 mb-1 z-10 bg-slate-900 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md shadow-xs flex items-center space-x-1 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            <span>{{ $val }} Hadir ({{ $persenKehadiran }}%)</span>
                                        </div>
                                    @endif

                                    <div class="w-full max-w-[42px] rounded-t-xl transition-all duration-300 {{ $isToday ? 'bg-emerald-600 shadow-sm' : 'bg-[#D1F0DE] hover:bg-emerald-200' }}"
                                         style="height: {{ max(14, $heightPct) }}%">
                                    </div>

                                    <span class="text-[11px] font-bold mt-2.5 truncate {{ $isToday ? 'text-emerald-700' : 'text-slate-400' }}">
                                        {{ $dateLabel }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar Info Tiles -->
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100/80 text-center">
                    <div class="p-2.5 rounded-xl bg-slate-50/80">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Presensi 7 Hari</span>
                        <span class="text-sm font-extrabold text-slate-800">{{ array_sum($trendHadir) }} Siswa</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50/80">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Rata-rata Presensi</span>
                        <span class="text-sm font-extrabold text-emerald-700">{{ count($trendHadir) > 0 ? round(array_sum($trendHadir) / count($trendHadir), 1) : 0 }} / Hari</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50/80">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase">Status Konsistensi</span>
                        <span class="text-sm font-extrabold text-emerald-600">● Optimal</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FULL WIDTH TABLE DI BAWAH: Status Presensi Rombel Hari Ini -->
    <div class="oripio-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100/80 gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-base shadow-2xs">
                    📋
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">Status Presensi Rombel Seluruh Kelas Hari Ini</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Pemantauan progres input presensi per rombel kelas tanggal {{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('tu.rekap.harian') }}"
                   class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition shadow-2xs">
                    <span>Buka Rekap Harian Lengkap</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-5">Rombel Kelas</th>
                        <th class="py-3 px-5">Wali Kelas</th>
                        <th class="py-3 px-3 text-center">Total Siswa</th>
                        <th class="py-3 px-3 text-center bg-emerald-50/60 text-emerald-800 font-extrabold border-l border-emerald-100">H (Hadir)</th>
                        <th class="py-3 px-3 text-center bg-blue-50/60 text-blue-800 font-extrabold border-l border-blue-100">I (Izin)</th>
                        <th class="py-3 px-3 text-center bg-amber-50/60 text-amber-800 font-extrabold border-l border-amber-100">S (Sakit)</th>
                        <th class="py-3 px-3 text-center bg-rose-50/60 text-rose-800 font-extrabold border-l border-rose-100">A (Alpa)</th>
                        <th class="py-3 px-4 text-center border-l border-slate-100">Tingkat Hadir</th>
                        <th class="py-3 px-5 text-center">Status Pengisian</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80">
                    @forelse($kelasList as $item)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-5 font-bold text-slate-900">
                                {{ $item['nama_kelas'] }}
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 font-medium">
                                {{ $item['wali_kelas'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-slate-700">
                                {{ $item['total_siswa'] }} Siswa
                            </td>
                            <td class="py-3.5 px-3 text-center font-extrabold text-emerald-700 bg-emerald-50/30 border-l border-emerald-100">
                                {{ $item['hadir'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-blue-700 bg-blue-50/30 border-l border-blue-100">
                                {{ $item['izin'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-amber-700 bg-amber-50/30 border-l border-amber-100">
                                {{ $item['sakit'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-extrabold text-rose-700 bg-rose-50/30 border-l border-rose-100">
                                {{ $item['alpa'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="font-extrabold text-slate-800 text-xs w-8 text-right">{{ $item['persen'] }}%</span>
                                    <div class="w-14 bg-slate-100 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $item['persen'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                @if($item['sudah_input'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ● Selesai Diisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                        ○ Belum Diisi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('tu.rekap.harian', ['kelas_id' => $item['id']]) }}"
                                   class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                    Lihat &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada rombel kelas terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
