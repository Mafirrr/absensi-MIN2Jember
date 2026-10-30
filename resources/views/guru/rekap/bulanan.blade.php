@extends('layouts.app')

@section('title', 'Rekap Bulanan Guru')
@section('page_title', 'Rekapitulasi Kehadiran Bulanan')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="custom-card p-8 text-center text-slate-500">
            <p>Anda belum ditugaskan mengelola rombel kelas.</p>
        </div>
    @else

        <!-- Filter Bar -->
        <div class="custom-card p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" action="{{ route('guru.rekap.bulanan') }}" class="flex flex-wrap items-center gap-3">
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Kelas</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
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
                            <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Kelas</span>
                            <span class="inline-block px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-100 font-extrabold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

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
                </form>
            </div>
        </div>

        <!-- Metric Badges Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-emerald-500">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">H</div>
                <div>
                    <span class="block text-[11px] font-bold text-emerald-800 uppercase tracking-tight">Hadir (H)</span>
                    <p class="text-2xl font-extrabold text-emerald-950 leading-tight mt-0.5">{{ $totalHadirBulan }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-blue-500">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">I</div>
                <div>
                    <span class="block text-[11px] font-bold text-blue-800 uppercase tracking-tight">Izin (I)</span>
                    <p class="text-2xl font-extrabold text-blue-950 leading-tight mt-0.5">{{ $totalIzinBulan }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-amber-500">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">S</div>
                <div>
                    <span class="block text-[11px] font-bold text-amber-800 uppercase tracking-tight">Sakit (S)</span>
                    <p class="text-2xl font-extrabold text-amber-950 leading-tight mt-0.5">{{ $totalSakitBulan }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-rose-500">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">A</div>
                <div>
                    <span class="block text-[11px] font-bold text-rose-800 uppercase tracking-tight">Alpa (A)</span>
                    <p class="text-2xl font-extrabold text-rose-950 leading-tight mt-0.5">{{ $totalAlpaBulan }}</p>
                </div>
            </div>
        </div>

        <!-- Matriks Bulanan -->
        <div class="oripio-card overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <span class="font-bold text-slate-800">
                    Kelas {{ $selectedKelas->nama_kelas }} &bull; {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
                </span>
                <div class="flex items-center space-x-3 font-bold text-[11px]">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span> H: Hadir
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 border border-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> I: Izin
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> S: Sakit
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-600"></span> A: Alpa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold">
                            <th class="py-3 px-3 text-left sticky left-0 bg-slate-50 z-10 w-44">Nama Siswa</th>
                            @foreach($monthDates as $date)
                                <th class="py-3 px-1 w-7 {{ $date->isWeekend() ? 'bg-slate-100/80 text-slate-300' : 'text-slate-600' }}">
                                    {{ $date->day }}
                                </th>
                            @endforeach
                            <th class="py-3 px-2 font-extrabold bg-emerald-100 text-emerald-800 border-l border-emerald-200">H</th>
                            <th class="py-3 px-2 font-extrabold bg-blue-100 text-blue-800 border-l border-blue-200">I</th>
                            <th class="py-3 px-2 font-extrabold bg-amber-100 text-amber-800 border-l border-amber-200">S</th>
                            <th class="py-3 px-2 font-extrabold bg-rose-100 text-rose-800 border-l border-rose-200">A</th>
                            <th class="py-3 px-2.5 font-extrabold bg-slate-100 text-slate-800 border-l border-slate-200">%</th>
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
                                    <td class="py-2 px-1 {{ $dayData['is_weekend'] ? 'bg-slate-50/60' : '' }}">
                                        @if($st === 'hadir')
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-emerald-50 text-emerald-700 font-extrabold text-[10px] ring-1 ring-emerald-200/60 shadow-2xs">H</span>
                                        @elseif($st === 'izin')
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 font-bold text-[10px] ring-1 ring-blue-200/60 shadow-2xs">I</span>
                                        @elseif($st === 'sakit')
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px] ring-1 ring-amber-200/60 shadow-2xs">S</span>
                                        @elseif($st === 'alpa')
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-rose-50 text-rose-700 font-extrabold text-[10px] ring-1 ring-rose-200/60 shadow-2xs">A</span>
                                        @else
                                            <span class="text-slate-200 text-[10px]">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="py-2 px-2 font-extrabold text-emerald-700 bg-emerald-50/50 border-l border-emerald-100">{{ $row['h'] }}</td>
                                <td class="py-2 px-2 font-bold text-blue-700 bg-blue-50/50 border-l border-blue-100">{{ $row['i'] }}</td>
                                <td class="py-2 px-2 font-bold text-amber-700 bg-amber-50/50 border-l border-amber-100">{{ $row['s'] }}</td>
                                <td class="py-2 px-2 font-extrabold text-rose-700 bg-rose-50/50 border-l border-rose-100">{{ $row['a'] }}</td>
                                <td class="py-2 px-2.5 font-extrabold text-slate-800 bg-slate-50 border-l border-slate-100">{{ $row['persen'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($monthDates) + 6 }}" class="py-8 text-center text-slate-400">
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
