@extends('layouts.app')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)
@section('page_title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">

    <!-- Back button & Class Info Banner -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('tu.kelas.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 mb-2">
                    Tingkat {{ $kelas->tingkat }} &bull; {{ $kelas->jurusan ?? 'Umum' }}
                </span>
                <h2 class="text-2xl font-bold text-slate-800">{{ $kelas->nama_kelas }}</h2>
                <p class="text-xs text-slate-500 mt-1">Tahun Ajaran: {{ $kelas->tahun_ajaran }}</p>
            </div>

            <div class="flex items-center space-x-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <span class="block text-[11px] font-semibold text-slate-500 uppercase">Wali Kelas</span>
                    <span class="text-sm font-bold text-slate-800">
                        {{ $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum Ditentukan' }}
                    </span>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <div>
                    <span class="block text-[11px] font-semibold text-slate-500 uppercase">Total Siswa</span>
                    <span class="text-sm font-bold text-blue-600">{{ $kelas->siswa->count() }} Siswa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List in Class -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-800">Daftar Siswa di Kelas Ini</h3>
            <a href="{{ route('tu.siswa.index', ['kelas_id' => $kelas->id]) }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800">
                Kelola Siswa di Halaman Siswa &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6 text-center w-16">No Absen</th>
                        <th class="py-3 px-6">NIS</th>
                        <th class="py-3 px-6">NISN</th>
                        <th class="py-3 px-6">Nama Lengkap</th>
                        <th class="py-3 px-6 text-center">L/P</th>
                        <th class="py-3 px-6">No HP Orang Tua</th>
                        <th class="py-3 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelas->siswa as $siswa)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-6 text-center font-bold text-slate-500">
                                {{ $siswa->no_absen ?? '-' }}
                            </td>
                            <td class="py-3 px-6 font-mono text-xs text-slate-600">
                                {{ $siswa->nis }}
                            </td>
                            <td class="py-3 px-6 font-mono text-xs text-slate-500">
                                {{ $siswa->nisn ?? '-' }}
                            </td>
                            <td class="py-3 px-6 font-semibold text-slate-800">
                                {{ $siswa->nama_lengkap }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $siswa->jenis_kelamin == 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $siswa->jenis_kelamin }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-xs text-slate-600">
                                {{ $siswa->no_telepon_ortu ?? '-' }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada siswa terdaftar di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
