@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="bg-white border border-stone-200 p-6 rounded-xl text-stone-600 text-xs">
            <p class="font-semibold text-stone-900 mb-1">Informasi:</p>
            <p>Akun Anda belum ditugaskan sebagai wali kelas atau guru pengampu. Hubungi bagian Tata Usaha untuk penugasan kelas.</p>
        </div>
    @else

        <!-- Top Header & Class Selector -->
        <div class="bg-white rounded-xl border border-stone-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-[10px] font-semibold text-stone-400 uppercase tracking-wider">Rombel Dikelola</span>
                <h2 class="text-base font-semibold text-stone-900 mt-0.5">Kelas {{ $selectedKelas->nama_kelas }}</h2>
                <p class="text-xs text-stone-500 mt-0.5">Wali Kelas: {{ auth()->user()->name }}</p>
            </div>

            <div class="flex items-center space-x-2">
                @if($kelasList->count() > 1)
                    <form method="GET" action="{{ route('guru.dashboard') }}" class="inline-block">
                        <select name="kelas_id" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <a href="{{ route('guru.absensi.index', ['kelas_id' => $selectedKelas->id]) }}"
                    class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition">
                    {{ $sudahInputHariIni ? 'Edit Absensi Hari Ini' : 'Input Absensi Hari Ini' }} &rarr;
                </a>
            </div>
        </div>

        <!-- Kolom Dasbord: Total Siswa, Kehadiran, Izin, Sakit, Alpa -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <!-- Total Siswa -->
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Total Siswa</span>
                <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $totalSiswa }}</p>
                <span class="text-[10px] text-stone-400">Siswa aktif</span>
            </div>

            <!-- Kehadiran (Hadir) -->
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Kehadiran (Hadir)</span>
                <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $hadirHariIni }}</p>
                <span class="text-[10px] text-emerald-600 font-medium">Hadir hari ini</span>
            </div>

            <!-- Izin -->
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Izin</span>
                <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $izinHariIni }}</p>
                <span class="text-[10px] text-stone-400">Izin hari ini</span>
            </div>

            <!-- Sakit -->
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Sakit</span>
                <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $sakitHariIni }}</p>
                <span class="text-[10px] text-stone-400">Sakit hari ini</span>
            </div>

            <!-- Alpa -->
            <div class="bg-white p-4 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Alpa</span>
                <p class="text-2xl font-semibold text-stone-900 mt-1">{{ $alpaHariIni }}</p>
                <span class="text-[10px] text-stone-400">Tanpa keterangan</span>
            </div>
        </div>

        <!-- Section: Statistik Kehadiran Per Status Siswa -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Statistik Kehadiran Per Status Siswa</h3>
                    <p class="text-[11px] text-stone-400">Akumulasi presensi per siswa bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
                </div>
                <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}" class="text-xs text-stone-600 hover:text-stone-900 font-medium">
                    Lihat Matriks Kalender &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Chart Ringkasan Minimalis -->
                <div class="bg-white p-4 rounded-xl border border-stone-200 flex flex-col justify-between">
                    <div>
                        <span class="text-[11px] font-medium text-stone-500 uppercase tracking-wider block mb-2">Proporsi Bulan Ini</span>
                        <div class="h-36 relative">
                            <canvas id="guruStatusChart"></canvas>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-stone-100 grid grid-cols-2 gap-2 text-xs text-stone-600">
                        <div>Hadir: <strong class="text-stone-900">{{ $totalHadirBulanIni }}</strong></div>
                        <div>Izin: <strong class="text-stone-900">{{ $totalIzinBulanIni }}</strong></div>
                        <div>Sakit: <strong class="text-stone-900">{{ $totalSakitBulanIni }}</strong></div>
                        <div>Alpa: <strong class="text-stone-900">{{ $totalAlpaBulanIni }}</strong></div>
                    </div>
                </div>

                <!-- Tabel Statistik Per Status Siswa -->
                <div class="lg:col-span-3 bg-white rounded-xl border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                                    <th class="py-2.5 px-3 text-center w-10">No</th>
                                    <th class="py-2.5 px-3">Nama Siswa</th>
                                    <th class="py-2.5 px-3 text-center">NIS</th>
                                    <th class="py-2.5 px-3 text-center">Hadir</th>
                                    <th class="py-2.5 px-3 text-center">Izin</th>
                                    <th class="py-2.5 px-3 text-center">Sakit</th>
                                    <th class="py-2.5 px-3 text-center">Alpa</th>
                                    <th class="py-2.5 px-3 text-center">% Hadir</th>
                                    <th class="py-2.5 px-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @forelse($statistikPerSiswa as $idx => $item)
                                    <tr class="hover:bg-stone-50/50">
                                        <td class="py-2 px-3 text-center text-stone-400">{{ $item['no_absen'] ?? ($idx + 1) }}</td>
                                        <td class="py-2 px-3 font-medium text-stone-900">{{ $item['nama_lengkap'] }}</td>
                                        <td class="py-2 px-3 text-center text-stone-500 font-mono">{{ $item['nis'] }}</td>
                                        <td class="py-2 px-3 text-center font-medium text-stone-800">{{ $item['hadir'] }}</td>
                                        <td class="py-2 px-3 text-center text-stone-600">{{ $item['izin'] }}</td>
                                        <td class="py-2 px-3 text-center text-stone-600">{{ $item['sakit'] }}</td>
                                        <td class="py-2 px-3 text-center text-stone-600">{{ $item['alpa'] }}</td>
                                        <td class="py-2 px-3 text-center font-semibold text-stone-900">{{ $item['persentase'] }}%</td>
                                        <td class="py-2 px-3 text-center">
                                            @if($item['alpa'] >= 3)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                    Perhatian
                                                </span>
                                            @elseif($item['persentase'] >= 90)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Baik
                                                </span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-600">
                                                    Cukup
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-6 text-center text-stone-400">Belum ada siswa di kelas ini.</td>
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
                backgroundColor: ['#292524', '#78716c', '#a8a29e', '#d6d3d1'],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '70%'
        }
    });
</script>
@endif
@endpush
