@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard')

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
                Akun Anda belum terdaftar sebagai wali kelas atau guru pengampu kelas. Silakan hubungi bagian Tata Usaha (TU) untuk penugasan kelas.
            </p>
        </div>
    @else

        <!-- Top Greeting Row Matching Screenshot -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    Welcome back {{ auth()->user()->name }}
                </h2>
                <p class="text-xs text-slate-400 font-medium mt-1">
                    Kelola absensi dan pantau kehadiran siswa aktif untuk <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong>.
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
                                class="px-3 py-1.5 rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-700 shadow-2xs focus:outline-none cursor-pointer">
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
                    <span>{{ now()->translatedFormat('D, d F Y') }}</span>
                </div>

                <!-- Input Attendance Button -->
                <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                   class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#1E293B] hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Input Absensi</span>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-700">Total Siswa Kelas</span>
                        </div>

                        <span class="inline-flex items-center text-[11px] font-bold text-slate-700 bg-slate-50 border border-slate-200/80 px-2 py-0.5 rounded-lg">
                            Kelas {{ $selectedKelas->nama_kelas }} ▾
                        </span>
                    </div>

                    <!-- Big Metric -->
                    <div class="mt-3">
                        <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ $totalSiswa }} Siswa
                        </div>
                        <div class="mt-1 flex items-center space-x-1.5">
                            @if($sudahInputHariIni)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                    ✓ Sudah Diabsen
                                </span>
                                <span class="text-[11px] font-medium text-slate-400">presensi hari ini lengkap</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">
                                    ! Belum Diabsen
                                </span>
                                <span class="text-[11px] font-medium text-slate-400">klik tombol input di bawah</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Row -->
                <div class="mt-5 grid grid-cols-2 gap-2.5 pt-1">
                    <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center justify-center space-x-1.5 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Input Absensi</span>
                    </a>

                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center justify-center space-x-1.5 py-2.5 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                            <span class="text-xs font-bold text-slate-700">Kehadiran (Hadir) Hari Ini</span>
                        </div>

                        <button class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="4" r="1.5" />
                                <circle cx="10" cy="10" r="1.5" />
                                <circle cx="10" cy="16" r="1.5" />
                            </svg>
                        </button>
                    </div>

                    @php
                        $persenHadirHariIni = $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100, 1) : 0;
                    @endphp
                    <div class="mt-3">
                        <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ $hadirHariIni }} Siswa
                        </div>
                        <div class="mt-1 flex items-center space-x-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                {{ $persenHadirHariIni }}%
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">tingkat kehadiran kelas hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-5 pt-1">
                    <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 mb-1.5">
                        <span>Progress Masuk Kelas</span>
                        <span class="text-slate-700">{{ $hadirHariIni }} / {{ $totalSiswa }} Siswa</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $persenHadirHariIni }}%"></div>
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
                            <span class="text-[11px] font-medium text-slate-400">tidak hadir hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- Mini Status Pills -->
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

            <!-- LEFT COLUMN (5 of 12 cols) -->
            <div class="lg:col-span-5 space-y-5">

                <!-- Widget 1: My Wallet Style -> Ringkasan Rombel -->
                <div class="oripio-card p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100/80">
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Profil Rombel Kelas</h3>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                            ● Kelas Aktif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="p-3 rounded-2xl border border-slate-150 bg-slate-50/50">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Nama Rombel</span>
                            <span class="block text-sm font-extrabold text-slate-900 mt-1">Kelas {{ $selectedKelas->nama_kelas }}</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5">{{ $selectedKelas->tingkat }} - {{ $selectedKelas->jurusan }}</span>
                        </div>

                        <div class="p-3 rounded-2xl border border-slate-150 bg-slate-50/50">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Wali Kelas</span>
                            <span class="block text-sm font-extrabold text-slate-900 mt-1 truncate">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] text-emerald-600 font-bold mt-0.5">Guru Pengampu</span>
                        </div>

                        <div class="p-3 rounded-2xl border border-slate-150 bg-slate-50/50">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tahun Ajaran</span>
                            <span class="block text-sm font-extrabold text-slate-900 mt-1">{{ $selectedKelas->tahun_ajaran }}</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5">Semester Ganjil</span>
                        </div>

                        <div class="p-3 rounded-2xl border border-slate-150 bg-slate-50/50">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Siswa</span>
                            <span class="block text-sm font-extrabold text-slate-900 mt-1">{{ $totalSiswa }} Siswa</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5">Aktif Terdaftar</span>
                        </div>
                    </div>
                </div>

                <!-- Widget 2: My Savings Plan Style -> Akumulasi Bulan Ini -->
                <div class="oripio-card p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100/80">
                        <div class="flex items-center space-x-2">
                            <span class="text-slate-400">📊</span>
                            <h3 class="text-xs font-bold text-slate-900 tracking-tight">Akumulasi Bulan Ini ({{ Carbon\Carbon::now()->translatedFormat('F Y') }})</h3>
                        </div>
                        <button class="text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="4" r="1.5" />
                                <circle cx="10" cy="10" r="1.5" />
                                <circle cx="10" cy="16" r="1.5" />
                            </svg>
                        </button>
                    </div>

                    @php
                        $grandTotal = $totalHadirBulanIni + $totalIzinBulanIni + $totalSakitBulanIni + $totalAlpaBulanIni;
                        $rateHadir = $grandTotal > 0 ? round(($totalHadirBulanIni / $grandTotal) * 100, 1) : 0;
                        $rateIzinSakit = $grandTotal > 0 ? round((($totalIzinBulanIni + $totalSakitBulanIni) / $grandTotal) * 100, 1) : 0;
                        $rateAlpa = $grandTotal > 0 ? round(($totalAlpaBulanIni / $grandTotal) * 100, 1) : 0;
                    @endphp

                    <div class="mt-4 space-y-4">
                        <!-- Hadir -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    <span class="font-bold text-slate-800">Tingkat Hadir</span>
                                </div>
                                <span class="font-extrabold text-emerald-700">{{ $rateHadir }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                                <span>{{ $totalHadirBulanIni }} catatan hadir</span>
                                <span>Target 95%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $rateHadir }}%"></div>
                            </div>
                        </div>

                        <!-- Izin & Sakit -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="font-bold text-slate-800">Izin & Sakit</span>
                                </div>
                                <span class="font-extrabold text-amber-600">{{ $rateIzinSakit }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                                <span>{{ $totalIzinBulanIni + $totalSakitBulanIni }} catatan terkonfirmasi</span>
                                <span>Maks 5%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $rateIzinSakit }}%"></div>
                            </div>
                        </div>

                        <!-- Alpa -->
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="font-bold text-slate-800">Alpa (Tanpa Keterangan)</span>
                                </div>
                                <span class="font-extrabold text-rose-600">{{ $rateAlpa }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5 font-medium">
                                <span>{{ $totalAlpaBulanIni }} ketidakhadiran</span>
                                <span>Target 0%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $rateAlpa }}%"></div>
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
                                <h3 class="text-xs font-bold text-slate-900 tracking-tight">Overview Kehadiran Kelas</h3>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    <span class="text-[11px]">Kehadiran</span>
                                </div>

                                <!-- Interactive Filter Periode Dropdown -->
                                <form method="GET" action="{{ route('guru.dashboard') }}" class="inline-flex items-center">
                                    @if($selectedKelas)
                                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                                    @endif
                                    <div class="relative">
                                        <select name="periode" onchange="this.form.submit()"
                                                class="appearance-none text-[11px] font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200/90 pl-2.5 pr-6 py-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer transition shadow-2xs">
                                            <option value="7" {{ $periode == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                                            <option value="14" {{ $periode == '14' ? 'selected' : '' }}>14 Hari Terakhir</option>
                                            <option value="30" {{ $periode == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                                            <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                        </select>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400 text-[8px]">
                                            ▼
                                        </span>
                                    </div>
                                </form>

                                <button type="button" onclick="alert('Overview grafik kehadiran kelas berdasarkan periode yang dipilih.')"
                                        class="text-slate-400 hover:text-slate-600 p-1 rounded-lg" title="Info Grafik">
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
                            <div class="h-56 flex items-end justify-between gap-1.5 sm:gap-2.5 px-2 overflow-x-auto">
                                @php
                                    $maxH = max(1, ...$trendHadir);
                                @endphp
                                @foreach($trendDates as $idx => $dateLabel)
                                    @php
                                        $val = $trendHadir[$idx] ?? 0;
                                        $heightPct = round(($val / $maxH) * 85);
                                        $isToday = $idx === (count($trendDates) - 1);
                                        $rateToday = $totalSiswa > 0 ? round(($val / $totalSiswa) * 100) : 0;
                                    @endphp
                                    <div class="flex-1 min-w-[20px] max-w-[42px] flex flex-col items-center group relative h-full justify-end">
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
                                             style="height: {{ max(14, $heightPct) }}%">
                                        </div>

                                        <span class="text-[10px] sm:text-[11px] font-bold mt-2.5 truncate {{ $isToday ? 'text-emerald-700' : 'text-slate-400' }}">
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
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Hadir ({{ $periodeLabel }})</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ array_sum($trendHadir) }} Presensi</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50/80">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Rata-rata Harian</span>
                            <span class="text-sm font-extrabold text-emerald-700">{{ count($trendHadir) > 0 ? round(array_sum($trendHadir) / count($trendHadir), 1) : 0 }} Siswa</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50/80">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Status Konsistensi</span>
                            <span class="text-sm font-extrabold text-emerald-600">● Stabil</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FULL WIDTH TABLE DI BAWAH: Statistik Kehadiran Per Status Siswa (Bulan Ini) -->
        <div class="oripio-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100/80 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-base shadow-2xs">
                        👥
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Statistik Kehadiran Per Status Siswa (Bulan Ini)</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Rekapitulasi lengkap presensi per siswa Kelas {{ $selectedKelas->nama_kelas }} periode {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Legend Indicators -->
                    <div class="hidden md:flex items-center space-x-2 text-[10px] font-bold">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> H: Hadir
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 border border-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> I: Izin
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> S: Sakit
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> A: Alpa
                        </span>
                    </div>

                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition shadow-2xs">
                        <span>Buka Rekap Matriks Lengkap</span>
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
                            <th class="py-3 px-4 text-center w-14">No</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-5">Nama Lengkap Siswa</th>
                            <th class="py-3 px-4 text-center">L/P</th>
                            <th class="py-3 px-3 text-center bg-emerald-50/60 text-emerald-800 font-extrabold border-l border-emerald-100">H (Hadir)</th>
                            <th class="py-3 px-3 text-center bg-blue-50/60 text-blue-800 font-extrabold border-l border-blue-100">I (Izin)</th>
                            <th class="py-3 px-3 text-center bg-amber-50/60 text-amber-800 font-extrabold border-l border-amber-100">S (Sakit)</th>
                            <th class="py-3 px-3 text-center bg-rose-50/60 text-rose-800 font-extrabold border-l border-rose-100">A (Alpa)</th>
                            <th class="py-3 px-4 text-center border-l border-slate-100">Total Hari</th>
                            <th class="py-3 px-5 text-center">Persentase</th>
                            <th class="py-3 px-4 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($statistikPerSiswa as $s)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-500">
                                    {{ $s['no_absen'] }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-500">
                                    {{ $s['nis'] }}
                                </td>
                                <td class="py-3.5 px-5 font-bold text-slate-900">
                                    {{ $s['nama_lengkap'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] {{ $s['jenis_kelamin'] == 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                        {{ $s['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-center font-extrabold text-emerald-700 bg-emerald-50/30 border-l border-emerald-100">
                                    {{ $s['hadir'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-bold text-blue-700 bg-blue-50/30 border-l border-blue-100">
                                    {{ $s['izin'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-bold text-amber-700 bg-amber-50/30 border-l border-amber-100">
                                    {{ $s['sakit'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-extrabold text-rose-700 bg-rose-50/30 border-l border-rose-100">
                                    {{ $s['alpa'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-slate-600 border-l border-slate-100">
                                    {{ $s['total'] }} Hari
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="font-extrabold text-slate-800 text-xs w-10 text-right">{{ $s['persentase'] }}%</span>
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ min(100, $s['persentase']) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($s['persentase'] >= 90)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ● Baik
                                        </span>
                                    @elseif($s['persentase'] >= 75)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ● Cukup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ● Perlu Perhatian
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-8 text-center text-slate-400 text-xs">
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
