@extends('layouts.app')

@section('title', 'Rekap Harian')
@section('page_title', 'Rekapitulasi Kehadiran Harian')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Harian</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Pantau dan verifikasi kehadiran siswa harian per rombel kelas.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-semibold text-slate-600">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="custom-card p-5">
        <form method="GET" action="{{ route('tu.rekap.harian') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Rombel Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Numbers Matching Screenshot Style -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-slate-400">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                #
            </div>
            <div>
                <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight">Tercatat</span>
                <p class="text-xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $totalSiswaTercatat }}</p>
            </div>
        </div>
        <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-emerald-500">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">
                H
            </div>
            <div>
                <span class="block text-[11px] font-bold text-emerald-800 uppercase tracking-tight">Hadir</span>
                <p class="text-xl font-extrabold text-emerald-950 leading-tight mt-0.5">{{ $totalHadir }}</p>
            </div>
        </div>
        <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-blue-500">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">
                I
            </div>
            <div>
                <span class="block text-[11px] font-bold text-blue-800 uppercase tracking-tight">Izin</span>
                <p class="text-xl font-extrabold text-blue-950 leading-tight mt-0.5">{{ $totalIzin }}</p>
            </div>
        </div>
        <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-amber-500">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">
                S
            </div>
            <div>
                <span class="block text-[11px] font-bold text-amber-800 uppercase tracking-tight">Sakit</span>
                <p class="text-xl font-extrabold text-amber-950 leading-tight mt-0.5">{{ $totalSakit }}</p>
            </div>
        </div>
        <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-rose-500">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">
                A
            </div>
            <div>
                <span class="block text-[11px] font-bold text-rose-800 uppercase tracking-tight">Alpa</span>
                <p class="text-xl font-extrabold text-rose-950 leading-tight mt-0.5">{{ $totalAlpa }}</p>
            </div>
        </div>
    </div>

    <!-- Table of Records -->
    <div class="oripio-card overflow-hidden">
        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between text-xs">
            <span class="font-bold text-slate-800">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
            <span class="text-slate-400 font-medium">{{ $selectedKelas ? 'Kelas ' . $selectedKelas->nama_kelas : 'Semua Rombel' }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5 text-center w-12">No</th>
                        <th class="py-3 px-5">NIS</th>
                        <th class="py-3 px-5">Nama Siswa</th>
                        <th class="py-3 px-5">Kelas</th>
                        <th class="py-3 px-5 text-center">Status</th>
                        <th class="py-3 px-5">Keterangan</th>
                        <th class="py-3 px-5">Pencatat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensiList as $index => $abs)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-5 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                            <td class="py-3 px-5 font-mono text-slate-600">{{ $abs->siswa->nis }}</td>
                            <td class="py-3 px-5 font-bold text-slate-900">{{ $abs->siswa->nama_lengkap }}</td>
                            <td class="py-3 px-5 text-slate-600 font-medium">{{ $abs->kelas->nama_kelas }}</td>
                            <td class="py-3 px-5 text-center">
                                @if($abs->status == 'hadir')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ● Hadir
                                    </span>
                                @elseif($abs->status == 'izin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                        ● Izin
                                    </span>
                                @elseif($abs->status == 'sakit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        ● Sakit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        ● Alpa
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-slate-500 italic">{{ $abs->catatan ?? '-' }}</td>
                            <td class="py-3 px-5 text-slate-400">{{ $abs->guru ? $abs->guru->name : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data absensi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
