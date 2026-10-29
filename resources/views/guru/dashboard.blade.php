@extends('layouts.app')

@section('title', 'Dashboard Guru / Wali Kelas')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">

    <!-- Header & Class Selector Banner -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-700 to-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur text-xs font-semibold uppercase tracking-wider mb-2">
                    Panel Guru / Wali Kelas
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Halo, {{ auth()->user()->name }}
                </h2>
                <p class="text-teal-100 text-sm mt-1">
                    @if($selectedKelas)
                        Anda saat ini mengelola absensi untuk <strong class="text-white">Kelas {{ $selectedKelas->nama_kelas }}</strong>
                    @else
                        Anda belum memiliki rombel kelas yang ditugaskan.
                    @endif
                </p>
            </div>

            <!-- Kelas Switcher (Jika guru mengajar > 1 kelas) -->
            @if($kelasList->count() > 1)
                <div class="bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/20">
                    <form method="GET" action="{{ route('guru.dashboard') }}" class="flex items-center space-x-2">
                        <label class="text-xs font-medium text-teal-100">Ganti Kelas:</label>
                        <select name="kelas_id" onchange="this.form.submit()" class="bg-slate-900/80 text-white text-xs font-semibold rounded-lg px-3 py-1.5 border border-white/20 focus:ring-2 focus:ring-emerald-400">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $selectedKelas && $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    @if(!$selectedKelas)
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-2xl text-amber-800 text-sm">
            <p class="font-bold">Perhatian:</p>
            <p>Akun Anda belum terdaftar sebagai wali kelas atau pengampu rombel. Silakan hubungi bagian Tata Usaha (TU) untuk pengaturan kelas.</p>
        </div>
    @else

        <!-- Kolom Dashboard: Total Siswa, Kehadiran, Izin, Sakit, Alpa -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <!-- Total Siswa -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Siswa</span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-slate-800">{{ $totalSiswa }}</p>
                    <span class="text-[11px] text-slate-400">Kelas {{ $selectedKelas->nama_kelas }}</span>
                </div>
            </div>

            <!-- Kehadiran (Hadir) -->
            <div class="bg-emerald-50/70 p-5 rounded-2xl border border-emerald-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Kehadiran (Hadir)</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs">
                        H
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-emerald-800">{{ $hadirHariIni }}</p>
                    <span class="text-[11px] text-emerald-600 font-medium">Hari ini tercatat hadir</span>
                </div>
            </div>

            <!-- Izin -->
            <div class="bg-blue-50/70 p-5 rounded-2xl border border-blue-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Izin</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center font-bold text-xs">
                        I
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-blue-800">{{ $izinHariIni }}</p>
                    <span class="text-[11px] text-blue-600 font-medium">Hari ini izin keperluan</span>
                </div>
            </div>

            <!-- Sakit -->
            <div class="bg-amber-50/70 p-5 rounded-2xl border border-amber-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Sakit</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs">
                        S
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-amber-800">{{ $sakitHariIni }}</p>
                    <span class="text-[11px] text-amber-600 font-medium">Hari ini surat sakit</span>
                </div>
            </div>

            <!-- Alpa -->
            <div class="bg-rose-50/70 p-5 rounded-2xl border border-rose-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wider">Alpa</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center font-bold text-xs">
                        A
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-extrabold text-rose-800">{{ $alpaHariIni }}</p>
                    <span class="text-[11px] text-rose-600 font-medium">Tanpa keterangan</span>
                </div>
            </div>
        </div>

        <!-- Quick Action Alert if Today's Attendance is not yet input -->
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 rounded-2xl {{ $sudahInputHariIni ? 'bg-emerald-50 border border-emerald-200' : 'bg-amber-50 border border-amber-200' }} shadow-xs gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl {{ $sudahInputHariIni ? 'bg-emerald-500' : 'bg-amber-500' }} text-white flex items-center justify-center flex-shrink-0">
                    @if($sudahInputHariIni)
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-bold {{ $sudahInputHariIni ? 'text-emerald-900' : 'text-amber-900' }}">
                        {{ $sudahInputHariIni ? 'Absensi Hari Ini Sudah Diisi' : 'Absensi Hari Ini Belum Diisi!' }}
                    </h4>
                    <p class="text-xs {{ $sudahInputHariIni ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $sudahInputHariIni ? 'Data absensi kelas ' . $selectedKelas->nama_kelas . ' untuk hari ini telah tersimpan. Anda tetap dapat mengedit jika ada perubahan.' : 'Silakan lakukan pencatatan kehadiran siswa untuk kelas ' . $selectedKelas->nama_kelas . ' hari ini.' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                class="px-5 py-2.5 rounded-xl {{ $sudahInputHariIni ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-600 hover:bg-amber-700' }} text-white text-xs font-bold shadow-sm transition flex-shrink-0">
                {{ $sudahInputHariIni ? 'Perbarui Absensi Hari Ini' : 'Input Absensi Sekarang' }} &rarr;
            </a>
        </div>

        <!-- Section: Statistik Kehadiran Per Status Siswa -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">Statistik Kehadiran Per Status Siswa</h3>
                    <p class="text-xs text-slate-500">
                        Rekapitulasi total Hadir, Izin, Sakit, dan Alpa per individu siswa bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </p>
                </div>

                <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}"
                    class="inline-flex items-center text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                    Lihat Matriks Kalender Bulanan Lengkap &rarr;
                </a>
            </div>

            <!-- Visual Graphic Summary & Table -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Doughnut Chart Distribution -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col items-center justify-center">
                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Distribusi Kehadiran Kelas</h4>
                    <div class="w-44 h-44 relative">
                        <canvas id="guruStatusChart"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2 w-full text-xs text-slate-600">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Hadir: <strong>{{ $totalHadirBulanIni }}</strong></span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                            <span>Izin: <strong>{{ $totalIzinBulanIni }}</strong></span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span>Sakit: <strong>{{ $totalSakitBulanIni }}</strong></span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span>Alpa: <strong>{{ $totalAlpaBulanIni }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Table Statistik Kehadiran Per Siswa -->
                <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="py-3 px-4 text-center w-12">No</th>
                                    <th class="py-3 px-4">Nama Siswa</th>
                                    <th class="py-3 px-4 text-center">NIS</th>
                                    <th class="py-3 px-4 text-center">Hadir</th>
                                    <th class="py-3 px-4 text-center">Izin</th>
                                    <th class="py-3 px-4 text-center">Sakit</th>
                                    <th class="py-3 px-4 text-center">Alpa</th>
                                    <th class="py-3 px-4 text-center">% Hadir</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($statistikPerSiswa as $idx => $item)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3 px-4 text-center font-bold text-slate-400">
                                            {{ $item['no_absen'] ?? ($idx + 1) }}
                                        </td>
                                        <td class="py-3 px-4 font-bold text-slate-800">
                                            {{ $item['nama_lengkap'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-mono text-slate-500">
                                            {{ $item['nis'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-emerald-600 bg-emerald-50/30">
                                            {{ $item['hadir'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-blue-600 bg-blue-50/30">
                                            {{ $item['izin'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-amber-600 bg-amber-50/30">
                                            {{ $item['sakit'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-rose-600 bg-rose-50/30">
                                            {{ $item['alpa'] }}
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            <span class="{{ $item['persentase'] >= 85 ? 'text-emerald-700' : ($item['persentase'] >= 75 ? 'text-amber-700' : 'text-rose-700') }}">
                                                {{ $item['persentase'] }}%
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($item['alpa'] >= 3)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold bg-rose-100 text-rose-800 text-[10px]">
                                                    Perlu Perhatian
                                                </span>
                                            @elseif($item['persentase'] >= 90)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[10px]">
                                                    Sangat Baik
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold bg-slate-100 text-slate-600 text-[10px]">
                                                    Baik
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-8 text-center text-slate-400">
                                            Belum ada data siswa di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endif

</div>
@endsection

@push('scripts')
@if($selectedKelas)
<script>
    const ctx = document.getElementById('guruStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            datasets: [{
                data: [
                    {{ $totalHadirBulanIni }},
                    {{ $totalIzinBulanIni }},
                    {{ $totalSakitBulanIni }},
                    {{ $totalAlpaBulanIni }}
                ],
                backgroundColor: [
                    '#10b981', // Hadir
                    '#3b82f6', // Izin
                    '#f59e0b', // Sakit
                    '#ef4444'  // Alpa
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '65%'
        }
    });
</script>
@endif
@endpush
