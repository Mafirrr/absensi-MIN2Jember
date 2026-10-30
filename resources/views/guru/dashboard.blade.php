@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="oripio-card p-10 text-center">
            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-slate-800">Belum Ada Rombel Ditugaskan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Akun Anda belum terdaftar sebagai wali kelas atau guru pengampu kelas. Silakan hubungi bagian Tata Usaha (TU) untuk penugasan rombel kelas Anda.
            </p>
        </div>
    @else

        <!-- Header & Greeting Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Kelola absensi dan pantau kehadiran siswa untuk <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong>.
                </p>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Class Selector if has multiple classes -->
                @if($kelasList->count() > 1)
                    <form method="GET" action="{{ route('guru.dashboard') }}">
                        @if(isset($periode))
                            <input type="hidden" name="periode" value="{{ $periode }}">
                        @endif
                        <select name="kelas_id" onchange="this.form.submit()"
                                class="px-3.5 py-1.5 rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-700 shadow-2xs focus:outline-none cursor-pointer">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <!-- Date Pill Badge -->
                <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-semibold text-slate-600">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <!-- Input Attendance Button -->
                <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                   class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>{{ $sudahInputHariIni ? 'Perbarui Absensi' : 'Input Absensi' }}</span>
                </a>
            </div>
        </div>

        <!-- Banner Status Presensi Hari Ini (Informatif & Actionable) -->
        <div class="oripio-card p-5 {{ $sudahInputHariIni ? 'bg-gradient-to-r from-emerald-50 via-white to-white border-emerald-200' : 'bg-gradient-to-r from-amber-50 via-white to-white border-amber-200' }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-lg shadow-xs flex-shrink-0 {{ $sudahInputHariIni ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                        @if($sudahInputHariIni)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-extrabold text-slate-900">
                                @if($sudahInputHariIni)
                                    Presensi Kelas {{ $selectedKelas->nama_kelas }} Hari Ini Selesai Diisi
                                @else
                                    Presensi Kelas {{ $selectedKelas->nama_kelas }} Hari Ini Belum Diisi!
                                @endif
                            </h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sudahInputHariIni ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sudahInputHariIni ? 'Sudah Terdata' : 'Perlu Input' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($sudahInputHariIni)
                                Terdata: <strong>{{ $hadirHariIni }} Hadir</strong>, <strong>{{ $izinHariIni }} Izin</strong>, <strong>{{ $sakitHariIni }} Sakit</strong>, <strong>{{ $alpaHariIni }} Alpa</strong> dari total {{ $totalSiswa }} siswa.
                            @else
                                Data presensi untuk hari ini belum tersimpan. Silakan klik tombol di samping untuk mencatat presensi siswa kelas ini.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2.5 flex-shrink-0">
                    <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ $sudahInputHariIni ? 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200' : 'bg-amber-500 hover:bg-amber-600 text-white' }}">
                        <span>{{ $sudahInputHariIni ? 'Lihat / Edit Presensi' : 'Isi Presensi Sekarang' }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Clean KPI Stat Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Card 1: Total Siswa Kelas -->
            <div class="oripio-card p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Total Siswa Kelas</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $totalSiswa }} <span class="text-sm font-semibold text-slate-400">Siswa</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-500 font-medium">
                        Rombel <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong> &bull; Aktif
                    </div>
                </div>
            </div>

            <!-- Card 2: Hadir Hari Ini -->
            <div class="oripio-card p-5 flex flex-col justify-between border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-tight">Hadir Hari Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $hadirHariIni }} <span class="text-sm font-semibold text-slate-400">Siswa</span>
                    </div>
                    <div class="mt-1 flex items-center space-x-1.5 text-xs">
                        <span class="font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded-md">
                            {{ $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0 }}%
                        </span>
                        <span class="text-slate-400 font-medium">dari total siswa kelas</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Izin & Sakit Hari Ini -->
            <div class="oripio-card p-5 flex flex-col justify-between border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-700 uppercase tracking-tight">Izin & Sakit Hari Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $izinHariIni + $sakitHariIni }} <span class="text-sm font-semibold text-slate-400">Siswa</span>
                    </div>
                    <div class="mt-1 flex items-center space-x-2 text-xs font-medium">
                        <span class="text-blue-700 font-bold bg-blue-50 px-1.5 py-0.5 rounded-md">{{ $izinHariIni }} Izin</span>
                        <span class="text-amber-700 font-bold bg-amber-50 px-1.5 py-0.5 rounded-md">{{ $sakitHariIni }} Sakit</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Alpa Hari Ini -->
            <div class="oripio-card p-5 flex flex-col justify-between border-l-4 border-l-rose-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-tight">Alpa / Tanpa Keterangan</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $alpaHariIni }} <span class="text-sm font-semibold text-slate-400">Alpa</span>
                    </div>
                    <div class="mt-1 text-xs text-slate-500 font-medium">
                        @if($alpaHariIni > 0)
                            <span class="text-rose-600 font-bold">Perlu tindak lanjut wali kelas</span>
                        @else
                            <span class="text-emerald-600 font-bold">Nihil Alpa hari ini</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Grafik Tren Kehadiran Kelas (Full Width Melebar ke Samping) -->
        <div class="oripio-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100/80 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2.5 h-4 bg-emerald-600 rounded-xs"></div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Grafik Tren Kehadiran Siswa Kelas {{ $selectedKelas->nama_kelas }}</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Tren grafik kehadiran harian siswa untuk periode {{ $periodeLabel }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span class="text-xs">Jumlah Hadir</span>
                    </div>

                    <!-- Interactive Filter Periode Dropdown -->
                    <form method="GET" action="{{ route('guru.dashboard') }}" class="inline-flex items-center">
                        @if($selectedKelas)
                            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        @endif
                        <div class="relative">
                            <select name="periode" onchange="this.form.submit()"
                                    class="appearance-none text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 pl-3 pr-7 py-1.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer transition shadow-2xs">
                                <option value="7" {{ $periode == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                                <option value="14" {{ $periode == '14' ? 'selected' : '' }}>14 Hari Terakhir</option>
                                <option value="30" {{ $periode == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                                <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 text-[8px]">
                                ▼
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Custom Bar Chart Container -->
            <div class="pt-8 pb-4">
                <div class="h-60 flex items-end justify-between gap-1 sm:gap-2 lg:gap-3 px-1 sm:px-3 overflow-x-auto">
                    @php
                        $maxH = max(1, ...$trendHadir);
                        $totalB = count($trendDates);
                    @endphp
                    @foreach($trendDates as $idx => $dateLabel)
                        @php
                            $val = $trendHadir[$idx] ?? 0;
                            $heightPct = round(($val / $maxH) * 85);
                            $isToday = $idx === ($totalB - 1);
                            $rateToday = $totalSiswa > 0 ? round(($val / $totalSiswa) * 100) : 0;
                        @endphp
                        <div class="flex-1 min-w-[14px] sm:min-w-[20px] max-w-[48px] flex flex-col items-center group relative h-full justify-end">
                            @if($isToday)
                                <div class="absolute -top-7 mb-1 z-10 bg-slate-900 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md shadow-xs flex items-center space-x-1 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>{{ $val }} Hadir ({{ $rateToday }}%)</span>
                                </div>
                            @else
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-150 absolute -top-7 mb-1 z-20 bg-slate-900 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow whitespace-nowrap pointer-events-none">
                                    <span>{{ $val }} Hadir</span>
                                </div>
                            @endif

                            <div class="w-full rounded-t-xl transition-all duration-300 {{ $isToday ? 'bg-emerald-600 shadow-sm' : 'bg-[#D1F0DE] hover:bg-emerald-200' }}"
                                 style="height: {{ max(12, $heightPct) }}%">
                            </div>

                            <span class="text-[9px] sm:text-[10px] lg:text-[11px] font-bold mt-2.5 text-center leading-tight {{ $isToday ? 'text-emerald-700' : 'text-slate-400' }}">
                                @if(count($trendDates) > 14)
                                    <span class="hidden md:inline">{{ $dateLabel }}</span>
                                    <span class="md:hidden">{{ substr($dateLabel, 0, 2) }}</span>
                                @else
                                    {{ $dateLabel }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom Summary Tiles -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-100/80 text-center">
                <div class="p-3 rounded-xl bg-slate-50/80">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Hadir ({{ $periodeLabel }})</span>
                    <span class="text-base font-extrabold text-slate-800">{{ array_sum($trendHadir) }} Siswa</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50/80">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Rata-rata Kehadiran</span>
                    <span class="text-base font-extrabold text-emerald-700">{{ count($trendHadir) > 0 ? round(array_sum($trendHadir) / count($trendHadir), 1) : 0 }} Siswa / Hari</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50/80">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Kehadiran Tertinggi</span>
                    <span class="text-base font-extrabold text-emerald-600">{{ count($trendHadir) > 0 ? max($trendHadir) : 0 }} Siswa</span>
                </div>
            </div>
        </div>

        <!-- Tabel Ringkasan Akumulasi Presensi Siswa Kelas (Bulan Ini) -->
        <div class="oripio-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-base shadow-2xs">
                        👥
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Statistik Kehadiran Per Status Siswa</h3>
                        <p class="text-xs text-slate-400 font-medium">Daftar presensi siswa Kelas {{ $selectedKelas->nama_kelas }} periode {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition shadow-2xs">
                        <span>Buka Rekap Bulanan Lengkap</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3 text-center w-12">No</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-5">Nama Lengkap</th>
                            <th class="py-3 px-3 text-center">L/P</th>
                            <th class="py-3 px-3 text-center bg-emerald-50/70 text-emerald-800 font-extrabold border-l border-emerald-100">Hadir</th>
                            <th class="py-3 px-3 text-center bg-blue-50/70 text-blue-800 font-extrabold border-l border-blue-100">Izin</th>
                            <th class="py-3 px-3 text-center bg-amber-50/70 text-amber-800 font-extrabold border-l border-amber-100">Sakit</th>
                            <th class="py-3 px-3 text-center bg-rose-50/70 text-rose-800 font-extrabold border-l border-rose-100">Alpa</th>
                            <th class="py-3 px-4 text-center border-l border-slate-100">Tingkat Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($statistikPerSiswa as $idx => $s)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-3 text-center text-slate-400 font-bold">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-500 font-semibold">
                                    {{ $s['nis'] }}
                                </td>
                                <td class="py-3 px-5 font-bold text-slate-900">
                                    {{ $s['nama_lengkap'] }}
                                </td>
                                <td class="py-3 px-3 text-center text-slate-500 font-semibold">
                                    {{ $s['jenis_kelamin'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-extrabold text-emerald-700 bg-emerald-50/30 border-l border-emerald-100">
                                    {{ $s['hadir'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-blue-700 bg-blue-50/30 border-l border-blue-100">
                                    {{ $s['izin'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-amber-700 bg-amber-50/30 border-l border-amber-100">
                                    {{ $s['sakit'] }}
                                </td>
                                <td class="py-3 px-3 text-center font-extrabold text-rose-700 bg-rose-50/30 border-l border-rose-100">
                                    {{ $s['alpa'] }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="font-extrabold text-slate-800 text-xs w-10 text-right">{{ $s['persentase'] }}%</span>
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $s['persentase'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data siswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
@endsection
