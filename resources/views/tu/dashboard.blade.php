@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha')
@section('page_title', 'Dashboard Tata Usaha')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-indigo-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur text-xs font-semibold uppercase tracking-wider mb-3">
                Panel Tata Usaha (TU)
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}
            </h2>
            <p class="text-blue-100 text-sm mt-2 leading-relaxed">
                Anda memiliki akses administratif penuh untuk memantau kehadiran di semua kelas, mengelola rombel kelas, data siswa, dan mencetak rekapitulasi harian serta bulanan.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Kelas -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Kelas</span>
                <p class="text-2xl font-bold text-slate-800">{{ $totalKelas }}</p>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa Aktif</span>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Guru</span>
                <p class="text-2xl font-bold text-slate-800">{{ $totalGuru }}</p>
            </div>
        </div>

        <!-- Tingkat Kehadiran Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">% Hadir Hari Ini</span>
                <p class="text-2xl font-bold text-emerald-600">{{ $persenKehadiran }}%</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Status Absensi Hari Ini -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Hadir -->
        <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Hadir Hari Ini</span>
                <p class="text-2xl font-extrabold text-emerald-900 mt-0.5">{{ $hadirHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                H
            </div>
        </div>

        <!-- Izin -->
        <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Izin Hari Ini</span>
                <p class="text-2xl font-extrabold text-blue-900 mt-0.5">{{ $izinHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                I
            </div>
        </div>

        <!-- Sakit -->
        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Sakit Hari Ini</span>
                <p class="text-2xl font-extrabold text-amber-900 mt-0.5">{{ $sakitHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                S
            </div>
        </div>

        <!-- Alpa -->
        <div class="bg-rose-50/70 border border-rose-200/80 rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-rose-700 uppercase tracking-wider">Alpa (Tanpa Ket.)</span>
                <p class="text-2xl font-extrabold text-rose-900 mt-0.5">{{ $alpaHariIni }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                A
            </div>
        </div>
    </div>

    <!-- Chart Tren 7 Hari Terakhir -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">Grafik Tren Kehadiran Siswa</h3>
                <p class="text-xs text-slate-500">Statistik kehadiran 7 hari aktif terakhir seluruh kelas</p>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block mr-1"></span> Hadir</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block mr-1"></span> Izin</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block mr-1"></span> Sakit</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-rose-500 inline-block mr-1"></span> Alpa</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="tuTrendChart"></canvas>
        </div>
    </div>

    <!-- Status Kehadiran Per Kelas Hari Ini -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-800">Status Kehadiran Per Kelas Hari Ini</h3>
                <p class="text-xs text-slate-500">Monitoring penginputan absensi kelas oleh masing-masing guru / wali kelas</p>
            </div>
            <a href="{{ route('tu.rekap.harian') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700">
                Lihat Rekap Harian Lengkap &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6">Wali Kelas</th>
                        <th class="py-3 px-6 text-center">Total Siswa</th>
                        <th class="py-3 px-6 text-center">Hadir</th>
                        <th class="py-3 px-6 text-center">Izin</th>
                        <th class="py-3 px-6 text-center">Sakit</th>
                        <th class="py-3 px-6 text-center">Alpa</th>
                        <th class="py-3 px-6 text-center">Status Input</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelasList as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-6 font-semibold text-slate-800">
                                {{ $item['nama_kelas'] }}
                            </td>
                            <td class="py-3.5 px-6 text-slate-600 text-xs">
                                {{ $item['wali_kelas'] }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-medium">
                                {{ $item['total_siswa'] }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-bold text-emerald-700 bg-emerald-50">
                                    {{ $item['hadir'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-bold text-blue-700 bg-blue-50">
                                    {{ $item['izin'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-bold text-amber-700 bg-amber-50">
                                    {{ $item['sakit'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-bold text-rose-700 bg-rose-50">
                                    {{ $item['alpa'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if($item['sudah_input'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Sudah Diisi ({{ $item['persen'] }}%)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum Diisi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('tu.rekap.harian', ['kelas_id' => $item['id']]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-slate-400">
                                Belum ada data kelas yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('tuTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($dates),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($trendHadir),
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                },
                {
                    label: 'Izin',
                    data: @json($trendIzin),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                },
                {
                    label: 'Sakit',
                    data: @json($trendSakit),
                    backgroundColor: '#f59e0b',
                    borderRadius: 4,
                },
                {
                    label: 'Alpa',
                    data: @json($trendAlpa),
                    backgroundColor: '#ef4444',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
