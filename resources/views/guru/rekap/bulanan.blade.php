@extends('layouts.app')

@section('title', 'Rekap Bulanan Guru')
@section('page_title', 'Rekapitulasi Kehadiran Bulanan')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="bg-white border border-stone-200 p-6 rounded-xl text-stone-600 text-xs">
            <p>Anda belum ditugaskan mengelola rombel kelas.</p>
        </div>
    @else

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" action="{{ route('guru.rekap.bulanan') }}" class="flex flex-wrap items-center gap-3">
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[10px] font-medium text-stone-500 mb-1">Kelas</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <div>
                            <span class="block text-[10px] font-medium text-stone-500 mb-1">Kelas</span>
                            <span class="inline-block px-2.5 py-1.5 rounded-lg bg-stone-100 text-stone-800 font-semibold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-medium text-stone-500 mb-1">Bulan</label>
                        <select name="bulan" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-medium text-stone-500 mb-1">Tahun</label>
                        <select name="tahun" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <a href="{{ route('guru.rekap.bulanan', ['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $selectedKelas->id, 'print' => 1]) }}" target="_blank"
                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-stone-900 hover:bg-stone-800 text-white rounded-lg text-xs font-medium transition self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Rekap</span>
                </a>
            </div>
        </div>

        <!-- Metric Badges Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white p-3.5 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Total Hadir (H)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalHadirBulan }}</p>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Total Izin (I)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalIzinBulan }}</p>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Total Sakit (S)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalSakitBulan }}</p>
            </div>
            <div class="bg-white p-3.5 rounded-xl border border-stone-200">
                <span class="text-[11px] font-medium text-stone-500">Total Alpa (A)</span>
                <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalAlpaBulan }}</p>
            </div>
        </div>

        <!-- Matriks Bulanan -->
        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
            <div class="p-3.5 border-b border-stone-100 flex items-center justify-between text-xs text-stone-500">
                <span class="font-medium text-stone-900">
                    Kelas {{ $selectedKelas->nama_kelas }} &bull; {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
                </span>
                <div class="flex items-center space-x-3 text-[11px]">
                    <span>H: Hadir</span>
                    <span>I: Izin</span>
                    <span>S: Sakit</span>
                    <span>A: Alpa</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-xs">
                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-200 text-stone-600 font-medium">
                            <th class="py-2 px-3 text-left sticky left-0 bg-stone-50 z-10 w-44">Nama Siswa</th>
                            @foreach($monthDates as $date)
                                <th class="py-2 px-1 w-6 {{ $date->isWeekend() ? 'bg-stone-200/50 text-stone-400' : '' }}">
                                    {{ $date->day }}
                                </th>
                            @endforeach
                            <th class="py-2 px-1.5 font-semibold bg-stone-100">H</th>
                            <th class="py-2 px-1.5 font-semibold bg-stone-100">I</th>
                            <th class="py-2 px-1.5 font-semibold bg-stone-100">S</th>
                            <th class="py-2 px-1.5 font-semibold bg-stone-100">A</th>
                            <th class="py-2 px-2 font-semibold bg-stone-100">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($matrix as $row)
                            <tr class="hover:bg-stone-50/50">
                                <td class="py-2 px-3 text-left font-medium text-stone-900 sticky left-0 bg-white hover:bg-stone-50 z-10 border-r border-stone-100 truncate max-w-xs">
                                    {{ $row['siswa']->nama_lengkap }}
                                </td>
                                @foreach($monthDates as $date)
                                    @php
                                        $dayData = $row['daily'][$date->day];
                                        $st = $dayData['status'];
                                    @endphp
                                    <td class="py-2 px-1 {{ $dayData['is_weekend'] ? 'bg-stone-100/50' : '' }}">
                                        @if($st === 'hadir') <span class="font-medium text-stone-900">H</span>
                                        @elseif($st === 'izin') <span class="font-medium text-stone-600">I</span>
                                        @elseif($st === 'sakit') <span class="font-medium text-stone-600">S</span>
                                        @elseif($st === 'alpa') <span class="font-bold text-rose-600">A</span>
                                        @else <span class="text-stone-300">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="py-2 px-1.5 font-semibold text-stone-900">{{ $row['h'] }}</td>
                                <td class="py-2 px-1.5 text-stone-600">{{ $row['i'] }}</td>
                                <td class="py-2 px-1.5 text-stone-600">{{ $row['s'] }}</td>
                                <td class="py-2 px-1.5 text-stone-600">{{ $row['a'] }}</td>
                                <td class="py-2 px-2 font-semibold text-stone-900">{{ $row['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($monthDates) + 6 }}" class="py-6 text-center text-stone-400">
                                    Tidak ada data siswa.
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
