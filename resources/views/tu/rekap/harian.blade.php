@extends('layouts.app')

@section('title', 'Rekap Harian')
@section('page_title', 'Rekapitulasi Kehadiran Harian')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-xl border border-stone-200">
        <form method="GET" action="{{ route('tu.rekap.harian') }}" class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[10px] font-medium text-stone-500 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                </div>

                <div>
                    <label class="block text-[10px] font-medium text-stone-500 mb-1">Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <a href="{{ route('tu.rekap.harian', ['tanggal' => $tanggal, 'kelas_id' => $kelasId, 'print' => 1]) }}" target="_blank"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-stone-900 hover:bg-stone-800 text-white rounded-lg text-xs font-medium transition self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Lembar</span>
            </a>
        </form>
    </div>

    <!-- Summary Numbers -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-stone-200">
            <span class="text-[11px] font-medium text-stone-500">Tercatat</span>
            <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalSiswaTercatat }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-stone-200">
            <span class="text-[11px] font-medium text-stone-500">Hadir</span>
            <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalHadir }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-stone-200">
            <span class="text-[11px] font-medium text-stone-500">Izin</span>
            <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalIzin }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-stone-200">
            <span class="text-[11px] font-medium text-stone-500">Sakit</span>
            <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalSakit }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-stone-200">
            <span class="text-[11px] font-medium text-stone-500">Alpa</span>
            <p class="text-xl font-semibold text-stone-900 mt-0.5">{{ $totalAlpa }}</p>
        </div>
    </div>

    <!-- Table of Records -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="p-3.5 border-b border-stone-100 flex items-center justify-between text-xs text-stone-500">
            <span>Tanggal: <strong class="text-stone-900">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong></span>
            <span>{{ $selectedKelas ? 'Kelas ' . $selectedKelas->nama_kelas : 'Semua Rombel' }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4 text-center w-10">No</th>
                        <th class="py-2.5 px-4">NIS</th>
                        <th class="py-2.5 px-4">Nama Siswa</th>
                        <th class="py-2.5 px-4">Kelas</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4">Keterangan</th>
                        <th class="py-2.5 px-4">Pencatat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($absensiList as $index => $abs)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2 px-4 text-center text-stone-400">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 font-mono text-stone-600">{{ $abs->siswa->nis }}</td>
                            <td class="py-2 px-4 font-medium text-stone-900">{{ $abs->siswa->nama_lengkap }}</td>
                            <td class="py-2 px-4 text-stone-600">{{ $abs->kelas->nama_kelas }}</td>
                            <td class="py-2 px-4 text-center">
                                @if($abs->status == 'hadir')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Hadir</span>
                                @elseif($abs->status == 'izin')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">Izin</span>
                                @elseif($abs->status == 'sakit')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-stone-100 text-stone-700">Sakit</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">Alpa</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 text-stone-500">{{ $abs->catatan ?? '-' }}</td>
                            <td class="py-2 px-4 text-stone-400">{{ $abs->guru ? $abs->guru->name : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-stone-400">Tidak ada data absensi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
