@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="custom-card p-8 text-center text-slate-500">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-blue-50 text-brand-600 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum Ada Rombel Ditugaskan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Akun Anda belum memiliki data kelas binaan sebagai wali kelas atau guru pengampu. Silakan hubungi bagian Tata Usaha (TU) sekolah.
            </p>
        </div>
    @else

        <!-- Welcome Banner Matching Screenshot -->
        <div class="bg-gradient-to-r from-blue-50 via-sky-50/70 to-indigo-50/80 border border-blue-100/80 rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden shadow-xs">
            <div class="space-y-2 max-w-xl z-10">
                <p class="text-sm font-semibold text-slate-600 flex items-center gap-1.5">
                    Good Morning, <span class="text-lg">👋</span>
                </p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ auth()->user()->name }}!
                </h2>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                    Pengelolaan presensi siswa aktif untuk <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong>. Pantau statistik dan input absensi harian kelas Anda dengan mudah.
                </p>

                @if($kelasList->count() > 1)
                    <div class="pt-2">
                        <form method="GET" action="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-xs px-3 py-1.5 rounded-xl border border-blue-200">
                            <span class="text-[11px] font-bold text-slate-600">Ganti Kelas:</span>
                            <select name="kelas_id" onchange="this.form.submit()" class="bg-transparent text-xs font-bold text-brand-600 focus:outline-none cursor-pointer">
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Illustration: Stack of books & plant in pot matching screenshot -->
            <div class="hidden md:flex items-center justify-center flex-shrink-0 z-10 pr-4">
                <svg class="w-48 h-32" viewBox="0 0 240 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Plant in Pot -->
                    <ellipse cx="65" cy="140" rx="20" ry="6" fill="#cbd5e1" opacity="0.4" />
                    <path d="M54 110 H76 L73 140 H57 Z" fill="#ffffff" stroke="#94a3b8" stroke-width="2" />
                    <ellipse cx="65" cy="110" rx="11" ry="3" fill="#64748b" />
                    <!-- Stem & Leaves -->
                    <path d="M65 110 Q65 85 70 75" stroke="#15803d" stroke-width="3" stroke-linecap="round" fill="none" />
                    <path d="M66 98 Q52 92 56 80 Q68 85 66 98 Z" fill="#22c55e" />
                    <path d="M68 86 Q82 80 78 68 Q66 73 68 86 Z" fill="#16a34a" />
                    <path d="M70 75 Q62 60 70 52 Q78 62 70 75 Z" fill="#4ade80" />

                    <!-- Books Shadows -->
                    <ellipse cx="160" cy="148" rx="55" ry="10" fill="#94a3b8" opacity="0.3" />

                    <!-- Bottom Book (Blue) -->
                    <rect x="110" y="125" width="95" height="18" rx="3" fill="#1d4ed8" />
                    <rect x="115" y="127" width="88" height="14" fill="#ffffff" />
                    <rect x="110" y="125" width="12" height="18" rx="3" fill="#1e40af" />
                    <line x1="125" y1="130" x2="198" y2="130" stroke="#cbd5e1" stroke-width="1.5" />
                    <line x1="125" y1="134" x2="198" y2="134" stroke="#cbd5e1" stroke-width="1.5" />
                    <line x1="125" y1="138" x2="198" y2="138" stroke="#cbd5e1" stroke-width="1.5" />

                    <!-- Middle Book (Yellow/Gold) -->
                    <rect x="115" y="105" width="90" height="18" rx="3" fill="#eab308" />
                    <rect x="120" y="107" width="83" height="14" fill="#ffffff" />
                    <rect x="115" y="105" width="12" height="18" rx="3" fill="#ca8a04" />
                    <line x1="130" y1="110" x2="198" y2="110" stroke="#cbd5e1" stroke-width="1.5" />
                    <line x1="130" y1="114" x2="198" y2="114" stroke="#cbd5e1" stroke-width="1.5" />

                    <!-- Top Book (Cyan/Blue open-angle) -->
                    <rect x="122" y="85" width="80" height="18" rx="3" fill="#2563eb" />
                    <rect x="126" y="87" width="74" height="14" fill="#ffffff" />
                    <rect x="122" y="85" width="10" height="18" rx="3" fill="#1d4ed8" />
                    <line x1="135" y1="90" x2="195" y2="90" stroke="#cbd5e1" stroke-width="1.5" />
                    <line x1="135" y1="94" x2="195" y2="94" stroke="#cbd5e1" stroke-width="1.5" />

                    <!-- Little Flying Bird silhouettes -->
                    <path d="M210 50 Q215 45 220 50 Q225 45 230 50" stroke="#94a3b8" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    <path d="M195 62 Q199 58 203 62 Q207 58 211 62" stroke="#94a3b8" stroke-width="1.5" fill="none" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- 5 Metric Cards Row Matching Screenshot -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <!-- 1. Total Siswa (Blue icon box) -->
            <div class="custom-card p-4 flex items-center space-x-3.5 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-tight truncate">Total Siswa</span>
                    <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalSiswa }}</p>
                    <span class="text-[10px] text-slate-400 font-medium">Siswa Kelas</span>
                </div>
            </div>

            <!-- 2. Kehadiran (Hadir) (Green icon box) -->
            <div class="custom-card p-4 flex items-center space-x-3.5 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-tight truncate">Kehadiran (H)</span>
                    <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $hadirHariIni }}</p>
                    <span class="text-[10px] text-emerald-600 font-semibold">Hadir Hari Ini</span>
                </div>
            </div>

            <!-- 3. Izin (Purple/Indigo icon box) -->
            <div class="custom-card p-4 flex items-center space-x-3.5 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-tight truncate">Izin</span>
                    <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $izinHariIni }}</p>
                    <span class="text-[10px] text-purple-600 font-semibold">Siswa Izin</span>
                </div>
            </div>

            <!-- 4. Sakit (Amber/Yellow icon box) -->
            <div class="custom-card p-4 flex items-center space-x-3.5 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-tight truncate">Sakit</span>
                    <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $sakitHariIni }}</p>
                    <span class="text-[10px] text-amber-600 font-semibold">Siswa Sakit</span>
                </div>
            </div>

            <!-- 5. Attendance Percentage / Alpa (Pink/Rose icon box) -->
            <div class="custom-card p-4 flex items-center space-x-3.5 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-tight truncate">Kehadiran</span>
                    <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">
                        {{ $totalSiswa > 0 ? round(($hadirHariIni / $totalSiswa) * 100) : 0 }}%
                    </p>
                    <span class="text-[10px] text-rose-600 font-semibold">Alpa: {{ $alpaHariIni }}</span>
                </div>
            </div>
        </div>

        <!-- Middle Section: Table on Left & Notice Board on Right -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Statistik Kehadiran Per Status Siswa -->
            <div id="tabel-statistik-siswa" class="lg:col-span-2 custom-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Statistik Kehadiran Per Status Siswa</h3>
                        <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                            View All Results
                        </a>
                    </div>

                    <div class="overflow-x-auto mt-2">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-slate-400 font-bold border-b border-slate-100">
                                    <th class="py-3 px-3">Nama Siswa</th>
                                    <th class="py-3 px-3">NIS</th>
                                    <th class="py-3 px-2 text-center">Hadir</th>
                                    <th class="py-3 px-2 text-center">Izin</th>
                                    <th class="py-3 px-2 text-center">Sakit</th>
                                    <th class="py-3 px-2 text-center">Alpa</th>
                                    <th class="py-3 px-3 text-center">Grade</th>
                                    <th class="py-3 px-3 text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($statistikPerSiswa as $item)
                                    @php
                                        // Grade calculation
                                        $pct = $item['persentase'];
                                        if ($pct >= 95) { $grade = 'A+'; $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200'; }
                                        elseif ($pct >= 85) { $grade = 'A'; $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200'; }
                                        elseif ($pct >= 75) { $grade = 'B+'; $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200'; }
                                        elseif ($pct >= 65) { $grade = 'B'; $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200'; }
                                        else { $grade = 'C'; $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200'; }
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="py-3 px-3 font-bold text-slate-800">
                                            {{ $item['nama_lengkap'] }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-400 font-mono text-[11px]">
                                            {{ $item['nis'] }}
                                        </td>
                                        <td class="py-3 px-2 text-center font-bold text-emerald-600">
                                            {{ $item['hadir'] }}
                                        </td>
                                        <td class="py-3 px-2 text-center font-medium text-purple-600">
                                            {{ $item['izin'] }}
                                        </td>
                                        <td class="py-3 px-2 text-center font-medium text-amber-600">
                                            {{ $item['sakit'] }}
                                        </td>
                                        <td class="py-3 px-2 text-center font-medium text-rose-600">
                                            {{ $item['alpa'] }}
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $badgeClass }}">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-right font-bold text-slate-900">
                                            {{ $item['persentase'] }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-6 text-center text-slate-400">Belum ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Notice Board Matching Screenshot -->
            <div class="custom-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Notice Board</h3>
                        <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                            Kelola Absensi
                        </a>
                    </div>

                    <div class="mt-4 space-y-4">
                        <!-- Notice 1 -->
                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                    {{ $sudahInputHariIni ? 'Presensi Hari Ini Sudah Diisi' : 'Input Presensi Harian Siswa' }}
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                    {{ $sudahInputHariIni ? 'Data presensi hari ini telah disimpan. Anda tetap dapat melakukan pembaruan jika ada perubahan.' : 'Segera lakukan pencatatan kehadiran untuk rombel Anda sebelum jam pelajaran berakhir.' }}
                                </p>
                                <span class="text-[10px] font-bold text-brand-600 mt-1 block">
                                    {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Notice 2 -->
                        <div class="flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                    Rekapitulasi Bulanan Tersedia
                                </h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                    Laporan bulanan matriks kehadiran siap dicetak dengan tanda tangan wali kelas.
                                </p>
                                <span class="text-[10px] font-bold text-brand-600 mt-1 block">
                                    Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Notice 3: Quick Button -->
                        <div class="pt-2">
                            <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                                class="w-full flex items-center justify-center space-x-2 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/25 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>{{ $sudahInputHariIni ? 'Edit Absensi Hari Ini' : 'Input Absensi Sekarang' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom 3 Cards Row: Calendar, Quick Access, Performance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- 1. Academic Calendar Matching Screenshot -->
            <div class="custom-card p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight">Academic Calendar</h3>
                    <span class="text-xs font-bold text-brand-600">
                        {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </span>
                </div>

                <!-- Mini Calendar Grid -->
                <div class="text-center text-xs">
                    <div class="grid grid-cols-7 gap-1 font-bold text-slate-400 text-[10px] mb-2">
                        <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span class="text-rose-400">Sat</span><span class="text-rose-400">Sun</span>
                    </div>

                    @php
                        $startDayOfMonth = \Carbon\Carbon::now()->startOfMonth();
                        $daysInCurMonth = \Carbon\Carbon::now()->daysInMonth;
                        $firstDayWeekIndex = ($startDayOfMonth->dayOfWeekIso); // 1 = Monday
                    @endphp

                    <div class="grid grid-cols-7 gap-1 text-[11px] font-semibold text-slate-700">
                        @for($p = 1; $p < $firstDayWeekIndex; $p++)
                            <span class="py-1 text-slate-300">-</span>
                        @endfor

                        @for($d = 1; $d <= $daysInCurMonth; $d++)
                            @php
                                $isToday = ($d === now()->day);
                            @endphp
                            <span class="py-1 rounded-lg {{ $isToday ? 'bg-brand-600 text-white font-extrabold shadow-sm' : 'hover:bg-slate-100' }}">
                                {{ $d }}
                            </span>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- 2. Quick Access Matching Screenshot (6 Tile Icons) -->
            <div class="custom-card p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight">Quick Access</h3>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <!-- Tile 1: Input Absensi -->
                    <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-brand-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">Input Presensi</span>
                    </a>

                    <!-- Tile 2: Rekap Bulanan -->
                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-emerald-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">Rekap Bulan</span>
                    </a>

                    <!-- Tile 3: Cetak Laporan -->
                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id, 'print' => 1]) }}" target="_blank"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-purple-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">Cetak PDF</span>
                    </a>

                    <!-- Tile 4: Daftar Siswa -->
                    <a href="#tabel-statistik-siswa"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-amber-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">{{ $totalSiswa }} Siswa</span>
                    </a>

                    <!-- Tile 5: Info Kelas -->
                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-sky-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">Kelas {{ $selectedKelas->nama_kelas }}</span>
                    </a>

                    <!-- Tile 6: Panduan -->
                    <button type="button" onclick="document.getElementById('modalPanduanGuru').classList.remove('hidden')"
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-rose-50/80 transition flex flex-col items-center justify-center text-center group border border-slate-100 w-full">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mb-1.5 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-700 leading-tight">Panduan</span>
                    </button>
                </div>
            </div>

            <!-- 3. Performance Overview Matching Screenshot (Doughnut with % in center) -->
            <div class="custom-card p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight">Performance Overview</h3>
                    <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                        View Details
                    </a>
                </div>

                @php
                    $totalRecordedMonth = $totalHadirBulanIni + $totalIzinBulanIni + $totalSakitBulanIni + $totalAlpaBulanIni;
                    $persenHadir = $totalRecordedMonth > 0 ? round(($totalHadirBulanIni / $totalRecordedMonth) * 100) : 0;
                    $persenIzin = $totalRecordedMonth > 0 ? round(($totalIzinBulanIni / $totalRecordedMonth) * 100) : 0;
                    $persenSakit = $totalRecordedMonth > 0 ? round(($totalSakitBulanIni / $totalRecordedMonth) * 100) : 0;
                    $persenAlpa = $totalRecordedMonth > 0 ? round(($totalAlpaBulanIni / $totalRecordedMonth) * 100) : 0;
                @endphp

                <div class="flex items-center justify-between pt-2">
                    <!-- Donut Chart with Center Percentage Text -->
                    <div class="relative w-32 h-32 flex-shrink-0">
                        <canvas id="guruPerformanceChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-xl font-extrabold text-slate-900 leading-none">{{ $persenHadir }}%</span>
                            <span class="text-[9px] text-slate-400 font-semibold mt-0.5">Kehadiran</span>
                        </div>
                    </div>

                    <!-- Legend List Matching Screenshot -->
                    <div class="space-y-2 text-xs font-medium pl-3">
                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-600 text-[11px]">Hadir</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $persenHadir }}%</span>
                        </div>

                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-slate-600 text-[11px]">Izin</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $persenIzin }}%</span>
                        </div>

                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-slate-600 text-[11px]">Sakit</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $persenSakit }}%</span>
                        </div>

                        <div class="flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span class="text-slate-600 text-[11px]">Alpa</span>
                            </div>
                            <span class="font-bold text-slate-800 text-[11px]">{{ $persenAlpa }}%</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endif

</div>

<!-- Modal Panduan Guru -->
<div id="modalPanduanGuru" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-100 shadow-2xl text-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-brand-600 flex items-center justify-center">ℹ</span>
                Panduan Penggunaan Guru / Wali Kelas
            </h3>
            <button onclick="document.getElementById('modalPanduanGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-base">✕</button>
        </div>

        <div class="space-y-3 text-slate-600 leading-relaxed">
            <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100">
                <strong class="text-brand-900 block mb-1">1. Hak Akses Kelas</strong>
                <p>Guru dan Wali Kelas hanya memiliki hak akses untuk menginput dan melihat rekap di kelas yang telah ditugaskan oleh Tata Usaha.</p>
            </div>
            <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100">
                <strong class="text-emerald-900 block mb-1">2. Penginputan Presensi</strong>
                <p>Klik menu <strong>Input Absensi</strong> atau tombol cepat di Dashboard. Anda dapat memanfaatkan fitur <strong>"Tandai Semua Hadir"</strong> lalu cukup mengubah siswa yang izin, sakit, atau alpa sebelum menekan <strong>Simpan Absensi</strong>.</p>
            </div>
            <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100">
                <strong class="text-purple-900 block mb-1">3. Rekapitulasi & Cetak</strong>
                <p>Di menu <strong>Rekap Bulanan</strong>, Anda dapat melihat matriks kalender harian serta mencetak laporan resmi bertanda tangan wali kelas.</p>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="document.getElementById('modalPanduanGuru').classList.add('hidden')"
                class="px-5 py-2 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition">
                Mengerti
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($selectedKelas)
<script>
    const ctx = document.getElementById('guruPerformanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            datasets: [{
                data: [
                    {{ $totalHadirBulanIni ?: 1 }},
                    {{ $totalIzinBulanIni }},
                    {{ $totalSakitBulanIni }},
                    {{ $totalAlpaBulanIni }}
                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
</script>
@endif
@endpush
