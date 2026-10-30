@extends('layouts.app')

@section('title', 'Dashboard TU')
@section('page_title', 'Dashboard Tata Usaha')

@section('content')
<div class="space-y-6">

    <!-- Header & Greeting Row -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2">
        <div>
            <div class="flex items-center space-x-2">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    @if($selectedKelas)
                        Dashboard &mdash; Kelas {{ $selectedKelas->nama_kelas }}
                    @else
                        Dashboard
                    @endif
                </h2>
                @if($selectedKelas)
                    <a href="{{ route('tu.dashboard') }}"
                       class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 transition"
                       title="Kembali ke tampilan seluruh sekolah">
                        <span>✕</span>
                        <span>Reset Filter</span>
                    </a>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1">
                @if($selectedKelas)
                    Menampilkan data presensi dan siswa untuk <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong> (Wali Kelas: {{ $selectedKelas->waliKelas ? $selectedKelas->waliKelas->name : 'Belum ditentukan' }}).
                @else
                    Panel monitoring presensi dan kontrol absensi siswa sekolah secara real-time.
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Dropdown Rombel Kelas -->
            <form method="GET" action="{{ route('tu.dashboard') }}" class="inline-flex items-center">
                @if(request('periode'))
                    <input type="hidden" name="periode" value="{{ request('periode') }}">
                @endif
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    <select name="kelas_id" onchange="this.form.submit()"
                            class="appearance-none pl-9 pr-8 py-2 bg-white border border-slate-200/90 rounded-xl text-xs font-bold text-slate-800 shadow-2xs hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 cursor-pointer transition">
                        <option value="">Semua Kelas (Seluruh Sekolah)</option>
                        @foreach($allKelas as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelas && $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 text-[8px]">
                        ▼
                    </span>
                </div>
            </form>

            <!-- Quick Action Button -->
            <a href="{{ $selectedKelas ? route('tu.rekap.harian', ['kelas_id' => $selectedKelas->id]) : route('tu.rekap.harian') }}"
               class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                <span>Rekap Harian</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Banner Status Progres Hari Ini -->
    @if($selectedKelas)
        @php
            $kelasItem = $kelasList->firstWhere('id', $selectedKelas->id);
            $sudahDiabsen = $kelasItem ? $kelasItem['sudah_input'] : false;
        @endphp
        <div class="oripio-card p-5 {{ $sudahDiabsen ? 'bg-gradient-to-r from-emerald-50 via-white to-white border-emerald-200' : 'bg-gradient-to-r from-amber-50 via-white to-white border-amber-200' }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-lg shadow-xs flex-shrink-0 {{ $sudahDiabsen ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                        @if($sudahDiabsen)
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
                                @if($sudahDiabsen)
                                    Presensi Kelas {{ $selectedKelas->nama_kelas }} Telah Selesai Diisi
                                @else
                                    Presensi Kelas {{ $selectedKelas->nama_kelas }} Belum Diisi Hari Ini
                                @endif
                            </h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sudahDiabsen ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sudahDiabsen ? '✓ Selesai' : '⏳ Belum Input' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($sudahDiabsen)
                                Terdata <strong>{{ $hadirHariIni }} Hadir</strong>, <strong>{{ $izinHariIni }} Izin</strong>, <strong>{{ $sakitHariIni }} Sakit</strong>, <strong>{{ $alpaHariIni }} Alpa</strong> dari {{ $totalSiswa }} siswa.
                            @else
                                Guru pengampu / wali kelas ({{ $selectedKelas->waliKelas ? $selectedKelas->waliKelas->name : 'Belum ditentukan' }}) belum menginput absensi siswa untuk hari ini.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2.5 flex-shrink-0">
                    <a href="{{ route('tu.rekap.harian', ['kelas_id' => $selectedKelas->id]) }}"
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ $sudahDiabsen ? 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200' : 'bg-amber-500 hover:bg-amber-600 text-white' }}">
                        <span>Lihat Rekap Harian Kelas</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Banner Progres Seluruh Kelas -->
        <div class="oripio-card p-5 {{ $kelasBelumInput === 0 ? 'bg-gradient-to-r from-emerald-50 via-white to-white border-emerald-200' : 'bg-gradient-to-r from-amber-50 via-white to-white border-amber-200' }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-bold text-lg shadow-xs flex-shrink-0 {{ $kelasBelumInput === 0 ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }}">
                        @if($kelasBelumInput === 0)
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
                                @if($kelasBelumInput === 0)
                                    Seluruh Kelas Telah Mengisi Presensi Hari Ini
                                @else
                                    Progres Input Presensi: {{ $kelasSudahInput }} dari {{ $totalKelas }} Rombel Selesai
                                @endif
                            </h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $kelasBelumInput === 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $totalKelas > 0 ? round(($kelasSudahInput / $totalKelas) * 100) : 0 }}% Selesai
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($kelasBelumInput === 0)
                                Semua rombongan belajar kelas aktif telah selesai diabsen oleh masing-masing guru / wali kelas.
                            @else
                                Masih ada <strong>{{ $kelasBelumInput }} rombel kelas</strong> yang belum melakukan absensi. Pilih kelas pada dropdown di atas untuk fokus melihat satu kelas.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 flex-shrink-0">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span>{{ $kelasSudahInput }} Selesai Diisi</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold {{ $kelasBelumInput > 0 ? 'bg-amber-50 border border-amber-200 text-amber-800' : 'bg-slate-50 border border-slate-200 text-slate-600' }}">
                        <span class="w-2 h-2 rounded-full {{ $kelasBelumInput > 0 ? 'bg-amber-500' : 'bg-slate-400' }}"></span>
                        <span>{{ $kelasBelumInput }} Belum Diabsen</span>
                    </span>
                </div>
            </div>

            <!-- Completion Progress Bar -->
            <div class="mt-4 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full transition-all duration-500 {{ $kelasBelumInput === 0 ? 'bg-emerald-600' : 'bg-amber-500' }}"
                     style="width: {{ $totalKelas > 0 ? round(($kelasSudahInput / $totalKelas) * 100) : 0 }}%"></div>
            </div>
        </div>
    @endif

    <!-- 4 Clean KPI Stat Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Siswa -->
        <div class="oripio-card p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">
                    {{ $selectedKelas ? 'Total Siswa Kelas' : 'Total Siswa Aktif' }}
                </span>
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
                    @if($selectedKelas)
                        Rombel <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong> &bull; Aktif
                    @else
                        Tersebar di <strong>{{ $totalKelas }} Rombel</strong> &bull; <strong>{{ $totalGuru }} Guru</strong>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card 2: Siswa Hadir -->
        <div class="oripio-card p-5 flex flex-col justify-between border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-tight">Siswa Hadir Hari Ini</span>
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
                        {{ $persenKehadiran }}%
                    </span>
                    <span class="text-slate-400 font-medium">
                        {{ $selectedKelas ? 'dari siswa kelas ini' : 'dari siswa yang diabsen' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Izin & Sakit -->
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

        <!-- Card 4: Alpa & Belum Absen -->
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
                    @if($belumDiabsen > 0)
                        <span>{{ $belumDiabsen }} belum diabsen</span>
                    @else
                        <span class="text-emerald-600 font-bold">Semua data terverifikasi</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- TAMPILAN TABEL: DETAIL SISWA JIKA KELAS DIPILIH vs STATUS ROMBEL JIKA SEMUA KELAS -->
    @if($selectedKelas)
        <!-- Tabel Detail Siswa Kelas Terpilih Hari Ini -->
        <div class="oripio-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-base shadow-2xs">
                        👥
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Siswa & Presensi Kelas {{ $selectedKelas->nama_kelas }} Hari Ini</h3>
                        <p class="text-xs text-slate-400 font-medium">Tanggal: {{ now()->translatedFormat('l, d F Y') }} &bull; Wali Kelas: {{ $selectedKelas->waliKelas ? $selectedKelas->waliKelas->name : 'Belum ditentukan' }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('tu.rekap.harian', ['kelas_id' => $selectedKelas->id]) }}"
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
                        <tr class="bg-slate-50/70 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3 text-center w-12">No</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-5">Nama Lengkap Siswa</th>
                            <th class="py-3 px-3 text-center">L/P</th>
                            <th class="py-3 px-4 text-center">Status Kehadiran Hari Ini</th>
                            <th class="py-3 px-5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswaList as $idx => $s)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-3 text-center text-slate-400 font-bold">
                                    {{ $s['no_absen'] ?? ($idx + 1) }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 font-semibold">
                                    {{ $s['nis'] }}
                                </td>
                                <td class="py-3.5 px-5 font-bold text-slate-900">
                                    {{ $s['nama_lengkap'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center text-slate-500 font-semibold">
                                    {{ $s['jenis_kelamin'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($s['status'] === 'hadir')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ● Hadir
                                        </span>
                                    @elseif($s['status'] === 'izin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            ● Izin
                                        </span>
                                    @elseif($s['status'] === 'sakit')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ● Sakit
                                        </span>
                                    @elseif($s['status'] === 'alpa')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ● Alpa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                            ○ Belum Diabsen
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 text-xs">
                                    {{ $s['keterangan'] ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data siswa terdaftar di Kelas {{ $selectedKelas->nama_kelas }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Tabel Ringkasan Seluruh Rombel Kelas -->
        <div class="oripio-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-base shadow-2xs">
                        📋
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Status Presensi Rombel Seluruh Kelas Hari Ini</h3>
                        <p class="text-xs text-slate-400 font-medium">Pantau status pengisian presensi per rombel kelas tanggal {{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('tu.rekap.harian') }}"
                       class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 text-xs font-bold transition shadow-2xs">
                        <span>Lihat Rekap Harian Lengkap</span>
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
                            <th class="py-3 px-4">Rombel Kelas</th>
                            <th class="py-3 px-4">Wali Kelas</th>
                            <th class="py-3 px-3 text-center">Total Siswa</th>
                            <th class="py-3 px-3 text-center bg-emerald-50/70 text-emerald-800 font-extrabold border-l border-emerald-100">Hadir</th>
                            <th class="py-3 px-3 text-center bg-blue-50/70 text-blue-800 font-extrabold border-l border-blue-100">Izin</th>
                            <th class="py-3 px-3 text-center bg-amber-50/70 text-amber-800 font-extrabold border-l border-amber-100">Sakit</th>
                            <th class="py-3 px-3 text-center bg-rose-50/70 text-rose-800 font-extrabold border-l border-rose-100">Alpa</th>
                            <th class="py-3 px-4 text-center border-l border-slate-100">Tingkat Hadir</th>
                            <th class="py-3 px-4 text-center">Status Absensi</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kelasList as $item)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <a href="{{ route('tu.dashboard', ['kelas_id' => $item['id']]) }}" class="hover:text-emerald-700 hover:underline">
                                        Kelas {{ $item['nama_kelas'] }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
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
                                    @if($item['sudah_input'])
                                        <div class="flex items-center justify-center space-x-2">
                                            <span class="font-extrabold text-slate-800 text-xs w-8 text-right">{{ $item['persen'] }}%</span>
                                            <div class="w-14 bg-slate-100 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                                <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $item['persen'] }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($item['sudah_input'])
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            <span>Selesai Diisi</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Belum Diabsen</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('tu.dashboard', ['kelas_id' => $item['id']]) }}"
                                       class="inline-flex items-center space-x-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                        <span>Fokus Kelas</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data rombel kelas terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Grafik Tren Kehadiran Siswa (Full Width Melebar ke Samping) -->
    <div class="oripio-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100/80 gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-4 bg-emerald-600 rounded-xs"></div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">
                        @if($selectedKelas)
                            Grafik Tren Kehadiran Siswa Kelas {{ $selectedKelas->nama_kelas }}
                        @else
                            Grafik Tren Kehadiran Siswa Seluruh Sekolah
                        @endif
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium">Tren kehadiran harian siswa untuk periode {{ $periodeLabel }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <span class="text-xs">Jumlah Hadir</span>
                </div>

                <!-- Interactive Filter Periode Dropdown -->
                <form method="GET" action="{{ route('tu.dashboard') }}" class="inline-flex items-center">
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
                    $maxHadir = max(1, ...$trendHadir);
                    $totalBars = count($dates);
                @endphp
                @foreach($dates as $idx => $dateLabel)
                    @php
                        $val = $trendHadir[$idx] ?? 0;
                        $heightPct = round(($val / $maxHadir) * 85);
                        $isToday = $idx === ($totalBars - 1);
                        $rateDay = $totalSiswa > 0 ? round(($val / $totalSiswa) * 100) : 0;
                    @endphp
                    <div class="flex-1 min-w-[14px] sm:min-w-[20px] max-w-[48px] flex flex-col items-center group relative h-full justify-end">
                        @if($isToday)
                            <div class="absolute -top-7 mb-1 z-10 bg-slate-900 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-md shadow-xs flex items-center space-x-1 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span>{{ $val }} Hadir ({{ $rateDay }}%)</span>
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
                            @if(count($dates) > 14)
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

        <!-- Bottom Chart Summary Tiles -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-4 border-t border-slate-100/80 text-center">
            <div class="p-3 rounded-xl bg-slate-50/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Presensi Hadir ({{ $periodeLabel }})</span>
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

</div>
@endsection
