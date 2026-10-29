@extends('layouts.app')

@section('title', 'Rekap Bulanan TU')
@section('page_title', 'Rekapitulasi Kehadiran Bulanan')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('tu.rekap.bulanan') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Bulan -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Kelas *</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-5">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs">
                        Tampilkan Rekap
                    </button>
                </div>
            </div>

            <!-- Print Action -->
            <div class="pt-5">
                <a href="{{ route('tu.rekap.bulanan', ['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelasId, 'print' => 1]) }}" target="_blank"
                    class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Lembar Rekap</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Monthly Summary Badge Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase">Hadir (H)</span>
                <p class="text-2xl font-bold text-emerald-600 mt-0.5">{{ $totalHadirBulan }}</p>
            </div>
            <span class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs">H</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase">Izin (I)</span>
                <p class="text-2xl font-bold text-blue-600 mt-0.5">{{ $totalIzinBulan }}</p>
            </div>
            <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-xs">I</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase">Sakit (S)</span>
                <p class="text-2xl font-bold text-amber-600 mt-0.5">{{ $totalSakitBulan }}</p>
            </div>
            <span class="w-9 h-9 rounded-lg bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs">S</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase">Alpa (A)</span>
                <p class="text-2xl font-bold text-rose-600 mt-0.5">{{ $totalAlpaBulan }}</p>
            </div>
            <span class="w-9 h-9 rounded-lg bg-rose-100 text-rose-800 font-bold flex items-center justify-center text-xs">A</span>
        </div>
    </div>

    <!-- Monthly Matrix Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-slate-800">
                    Matriks Presensi: <span class="text-blue-600">{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</span>
                    @if($selectedKelas)
                        &bull; Kelas {{ $selectedKelas->nama_kelas }} (Wali: {{ $selectedKelas->waliKelas?->name ?? 'Belum ditentukan' }})
                    @endif
                </h3>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="inline-flex items-center"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-sm mr-1"></span> H = Hadir</span>
                <span class="inline-flex items-center"><span class="w-2.5 h-2.5 bg-blue-500 rounded-sm mr-1"></span> I = Izin</span>
                <span class="inline-flex items-center"><span class="w-2.5 h-2.5 bg-amber-500 rounded-sm mr-1"></span> S = Sakit</span>
                <span class="inline-flex items-center"><span class="w-2.5 h-2.5 bg-rose-500 rounded-sm mr-1"></span> A = Alpa</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100/80 border-b border-slate-200 text-slate-700">
                        <th class="py-2.5 px-3 text-left sticky left-0 bg-slate-100 z-10 w-44">Nama Siswa</th>
                        @foreach($monthDates as $date)
                            <th class="py-2.5 px-1.5 w-7 {{ $date->isWeekend() ? 'bg-slate-200/60 text-slate-400' : '' }}">
                                <span>{{ $date->day }}</span>
                            </th>
                        @endforeach
                        <th class="py-2.5 px-2 bg-emerald-50 text-emerald-800 font-bold">H</th>
                        <th class="py-2.5 px-2 bg-blue-50 text-blue-800 font-bold">I</th>
                        <th class="py-2.5 px-2 bg-amber-50 text-amber-800 font-bold">S</th>
                        <th class="py-2.5 px-2 bg-rose-50 text-rose-800 font-bold">A</th>
                        <th class="py-2.5 px-2 bg-slate-100 font-bold">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($matrix as $row)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2 px-3 text-left font-semibold text-slate-800 sticky left-0 bg-white hover:bg-slate-50 z-10 border-r border-slate-100 truncate max-w-xs">
                                <span>{{ $row['siswa']->nama_lengkap }}</span>
                            </td>
                            @foreach($monthDates as $date)
                                @php
                                    $dayData = $row['daily'][$date->day];
                                    $st = $dayData['status'];
                                @endphp
                                <td class="py-2 px-1 {{ $dayData['is_weekend'] ? 'bg-slate-100/50' : '' }}">
                                    @if($st === 'hadir')
                                        <span class="inline-block w-5 h-5 leading-5 rounded bg-emerald-100 text-emerald-800 font-bold">H</span>
                                    @elseif($st === 'izin')
                                        <span class="inline-block w-5 h-5 leading-5 rounded bg-blue-100 text-blue-800 font-bold">I</span>
                                    @elseif($st === 'sakit')
                                        <span class="inline-block w-5 h-5 leading-5 rounded bg-amber-100 text-amber-800 font-bold">S</span>
                                    @elseif($st === 'alpa')
                                        <span class="inline-block w-5 h-5 leading-5 rounded bg-rose-100 text-rose-800 font-bold">A</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-2 px-2 font-bold text-emerald-700 bg-emerald-50/40">{{ $row['h'] }}</td>
                            <td class="py-2 px-2 font-bold text-blue-700 bg-blue-50/40">{{ $row['i'] }}</td>
                            <td class="py-2 px-2 font-bold text-amber-700 bg-amber-50/40">{{ $row['s'] }}</td>
                            <td class="py-2 px-2 font-bold text-rose-700 bg-rose-50/40">{{ $row['a'] }}</td>
                            <td class="py-2 px-2 font-bold text-slate-700 bg-slate-50/50">{{ $row['persen'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($monthDates) + 6 }}" class="py-8 text-center text-slate-400">
                                Pilih kelas untuk menampilkan matriks rekap bulanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
