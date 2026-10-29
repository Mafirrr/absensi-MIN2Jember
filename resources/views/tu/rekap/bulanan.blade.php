@extends('layouts.app')

@section('title', 'Rekap Bulanan TU')
@section('page_title', 'Rekapitulasi Kehadiran Bulanan')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="custom-card p-5">
        <form method="GET" action="{{ route('tu.rekap.bulanan') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Rombel Kelas *</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                        @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <a href="{{ route('tu.rekap.bulanan', ['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelasId, 'print' => 1]) }}" target="_blank"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-600/25 transition self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Lembar Rekap</span>
            </a>
        </form>
    </div>

    <!-- Monthly Summary Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="custom-card p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs flex-shrink-0">H</div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase">Hadir</span>
                <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalHadirBulan }}</p>
            </div>
        </div>
        <div class="custom-card p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center font-bold text-xs flex-shrink-0">I</div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase">Izin</span>
                <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalIzinBulan }}</p>
            </div>
        </div>
        <div class="custom-card p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs flex-shrink-0">S</div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase">Sakit</span>
                <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalSakitBulan }}</p>
            </div>
        </div>
        <div class="custom-card p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs flex-shrink-0">A</div>
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase">Alpa</span>
                <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalAlpaBulan }}</p>
            </div>
        </div>
    </div>

    <!-- Monthly Matrix Table -->
    <div class="custom-card overflow-hidden">
        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between text-xs">
            <span class="font-bold text-slate-800">
                Kelas {{ $selectedKelas ? $selectedKelas->nama_kelas : '-' }} &bull; 
                {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </span>
            <div class="flex items-center space-x-3 font-bold text-[10px]">
                <span class="text-emerald-700">H: Hadir</span>
                <span class="text-brand-700">I: Izin</span>
                <span class="text-amber-700">S: Sakit</span>
                <span class="text-rose-700">A: Alpa</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-3 text-left sticky left-0 bg-slate-50/90 z-10 w-44">Nama Siswa</th>
                        @foreach($monthDates as $date)
                            <th class="py-3 px-1 w-6 {{ $date->isWeekend() ? 'bg-slate-100/70 text-slate-300' : '' }}">
                                {{ $date->day }}
                            </th>
                        @endforeach
                        <th class="py-3 px-2 font-extrabold bg-blue-50 text-brand-700">H</th>
                        <th class="py-3 px-2 font-extrabold bg-blue-50 text-brand-700">I</th>
                        <th class="py-3 px-2 font-extrabold bg-blue-50 text-brand-700">S</th>
                        <th class="py-3 px-2 font-extrabold bg-blue-50 text-brand-700">A</th>
                        <th class="py-3 px-2.5 font-extrabold bg-blue-50 text-brand-800">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($matrix as $row)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-3 text-left font-bold text-slate-900 sticky left-0 bg-white hover:bg-slate-50 z-10 border-r border-slate-100 truncate max-w-xs">
                                {{ $row['siswa']->nama_lengkap }}
                            </td>
                            @foreach($monthDates as $date)
                                @php
                                    $dayData = $row['daily'][$date->day];
                                    $st = $dayData['status'];
                                @endphp
                                <td class="py-2.5 px-1 {{ $dayData['is_weekend'] ? 'bg-slate-50/60' : '' }}">
                                    @if($st === 'hadir') <span class="font-extrabold text-emerald-600">H</span>
                                    @elseif($st === 'izin') <span class="font-bold text-brand-600">I</span>
                                    @elseif($st === 'sakit') <span class="font-bold text-amber-600">S</span>
                                    @elseif($st === 'alpa') <span class="font-bold text-rose-600">A</span>
                                    @else <span class="text-slate-200">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-2.5 px-2 font-extrabold text-slate-800 bg-blue-50/30">{{ $row['h'] }}</td>
                            <td class="py-2.5 px-2 font-bold text-slate-600 bg-blue-50/30">{{ $row['i'] }}</td>
                            <td class="py-2.5 px-2 font-bold text-slate-600 bg-blue-50/30">{{ $row['s'] }}</td>
                            <td class="py-2.5 px-2 font-bold text-slate-600 bg-blue-50/30">{{ $row['a'] }}</td>
                            <td class="py-2.5 px-2.5 font-extrabold text-slate-900 bg-blue-50/40">{{ $row['persen'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($monthDates) + 6 }}" class="py-8 text-center text-slate-400">
                                Pilih kelas untuk melihat matriks absensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
