@extends('layouts.app')

@section('title', 'Rekap Harian TU')
@section('page_title', 'Rekapitulasi Kehadiran Harian')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('tu.rekap.harian') }}" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Tanggal Selector -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Absensi</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Kelas Selector -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Rombel Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-5">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-xs">
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <!-- Print Button -->
            <div class="pt-5">
                <a href="{{ route('tu.rekap.harian', ['tanggal' => $tanggal, 'kelas_id' => $kelasId, 'print' => 1]) }}" target="_blank"
                    class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Rekap Harian</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-xs text-center">
            <span class="block text-[11px] font-bold text-slate-500 uppercase">Total Tercatat</span>
            <span class="text-2xl font-bold text-slate-800">{{ $totalSiswaTercatat }}</span>
        </div>
        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 text-center">
            <span class="block text-[11px] font-bold text-emerald-700 uppercase">Hadir</span>
            <span class="text-2xl font-bold text-emerald-800">{{ $totalHadir }}</span>
        </div>
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 text-center">
            <span class="block text-[11px] font-bold text-blue-700 uppercase">Izin</span>
            <span class="text-2xl font-bold text-blue-800">{{ $totalIzin }}</span>
        </div>
        <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 text-center">
            <span class="block text-[11px] font-bold text-amber-700 uppercase">Sakit</span>
            <span class="text-2xl font-bold text-amber-800">{{ $totalSakit }}</span>
        </div>
        <div class="bg-rose-50 p-4 rounded-xl border border-rose-200 text-center">
            <span class="block text-[11px] font-bold text-rose-700 uppercase">Alpa</span>
            <span class="text-2xl font-bold text-rose-800">{{ $totalAlpa }}</span>
        </div>
    </div>

    <!-- Table of Daily Attendance -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">
                Data Absensi Tanggal: <span class="text-blue-600">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
                @if($selectedKelas)
                    &bull; <span class="text-slate-600">Kelas {{ $selectedKelas->nama_kelas }}</span>
                @endif
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6 text-center w-12">No</th>
                        <th class="py-3 px-6">NIS</th>
                        <th class="py-3 px-6">Nama Siswa</th>
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6 text-center">Status Kehadiran</th>
                        <th class="py-3 px-6">Keterangan / Catatan</th>
                        <th class="py-3 px-6">Pencatat (Guru)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensiList as $index => $abs)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-6 text-center text-xs text-slate-400 font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3 px-6 font-mono text-xs text-slate-600">
                                {{ $abs->siswa->nis }}
                            </td>
                            <td class="py-3 px-6 font-semibold text-slate-800">
                                {{ $abs->siswa->nama_lengkap }}
                            </td>
                            <td class="py-3 px-6 text-xs text-slate-600">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-medium">
                                    {{ $abs->kelas->nama_kelas }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                @if($abs->status == 'hadir')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Hadir
                                    </span>
                                @elseif($abs->status == 'izin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        Izin
                                    </span>
                                @elseif($abs->status == 'sakit')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Sakit
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        Alpa
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-xs text-slate-600 italic">
                                {{ $abs->catatan ?? '-' }}
                            </td>
                            <td class="py-3 px-6 text-xs text-slate-500">
                                {{ $abs->guru ? $abs->guru->name : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada data absensi yang dicatat pada tanggal dan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
