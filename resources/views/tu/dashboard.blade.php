@extends('layouts.app')

@section('title', 'Dashboard TU')
@section('page_title', 'Ringkasan Tata Usaha')

@section('content')
<div class="space-y-6">

    <!-- Primary Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <span class="text-xs text-stone-500 font-medium">Total Rombel</span>
            <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $totalKelas }}</p>
            <span class="text-[11px] text-stone-400">Kelas terdaftar</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <span class="text-xs text-stone-500 font-medium">Siswa Aktif</span>
            <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $totalSiswa }}</p>
            <span class="text-[11px] text-stone-400">Siswa terdaftar</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <span class="text-xs text-stone-500 font-medium">Tenaga Pendidik</span>
            <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $totalGuru }}</p>
            <span class="text-[11px] text-stone-400">Guru / Wali Kelas</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <span class="text-xs text-stone-500 font-medium">% Kehadiran Hari Ini</span>
            <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $persenKehadiran }}%</p>
            <span class="text-[11px] text-stone-400">Tingkat kehadiran</span>
        </div>
    </div>

    <!-- Attendance Today Status -->
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
            <div>
                <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Rekap Presensi Hari Ini</h3>
                <p class="text-[11px] text-stone-400">Akumulasi seluruh kelas yang telah terinput</p>
            </div>
            <a href="{{ route('tu.rekap.harian') }}" class="text-xs text-stone-600 hover:text-stone-900 font-medium">
                Buka Rekap Harian &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 rounded-lg bg-stone-50 border border-stone-100">
                <span class="text-[11px] font-medium text-stone-500">Hadir (H)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $hadirHariIni }}</p>
            </div>
            <div class="p-3 rounded-lg bg-stone-50 border border-stone-100">
                <span class="text-[11px] font-medium text-stone-500">Izin (I)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $izinHariIni }}</p>
            </div>
            <div class="p-3 rounded-lg bg-stone-50 border border-stone-100">
                <span class="text-[11px] font-medium text-stone-500">Sakit (S)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $sakitHariIni }}</p>
            </div>
            <div class="p-3 rounded-lg bg-stone-50 border border-stone-100">
                <span class="text-[11px] font-medium text-stone-500">Alpa (A)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $alpaHariIni }}</p>
            </div>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Tren Kehadiran 7 Hari Terakhir</h3>
                <p class="text-[11px] text-stone-400">Grafik akumulasi kehadiran harian</p>
            </div>
        </div>
        <div class="h-60">
            <canvas id="tuTrendChart"></canvas>
        </div>
    </div>

    <!-- Class Attendance Status Table -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="p-4 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Status Input Rombel Hari Ini</h3>
            <span class="text-[11px] text-stone-400">{{ count($kelasList) }} Rombel</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4">Kelas</th>
                        <th class="py-2.5 px-4">Wali Kelas</th>
                        <th class="py-2.5 px-4 text-center">Siswa</th>
                        <th class="py-2.5 px-4 text-center">Hadir</th>
                        <th class="py-2.5 px-4 text-center">Izin</th>
                        <th class="py-2.5 px-4 text-center">Sakit</th>
                        <th class="py-2.5 px-4 text-center">Alpa</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($kelasList as $item)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2.5 px-4 font-semibold text-stone-900">{{ $item['nama_kelas'] }}</td>
                            <td class="py-2.5 px-4 text-stone-600">{{ $item['wali_kelas'] }}</td>
                            <td class="py-2.5 px-4 text-center text-stone-600">{{ $item['total_siswa'] }}</td>
                            <td class="py-2.5 px-4 text-center font-medium text-stone-800">{{ $item['hadir'] }}</td>
                            <td class="py-2.5 px-4 text-center font-medium text-stone-800">{{ $item['izin'] }}</td>
                            <td class="py-2.5 px-4 text-center font-medium text-stone-800">{{ $item['sakit'] }}</td>
                            <td class="py-2.5 px-4 text-center font-medium text-stone-800">{{ $item['alpa'] }}</td>
                            <td class="py-2.5 px-4 text-center">
                                @if($item['sudah_input'])
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah ({{ $item['persen'] }}%)
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-500">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <a href="{{ route('tu.rekap.harian', ['kelas_id' => $item['id']]) }}" class="text-stone-600 hover:text-stone-900 font-medium">
                                    Lihat &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-stone-400">Belum ada data kelas.</td>
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
                    backgroundColor: '#292524',
                    borderRadius: 2,
                },
                {
                    label: 'Izin',
                    data: @json($trendIzin),
                    backgroundColor: '#78716c',
                    borderRadius: 2,
                },
                {
                    label: 'Sakit',
                    data: @json($trendSakit),
                    backgroundColor: '#a8a29e',
                    borderRadius: 2,
                },
                {
                    label: 'Alpa',
                    data: @json($trendAlpa),
                    backgroundColor: '#d6d3d1',
                    borderRadius: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, grid: { color: '#f5f5f4' } }
            },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
            }
        }
    });
</script>
@endpush
