@extends('layouts.app')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)
@section('page_title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">

    <div class="flex items-center space-x-2">
        <a href="{{ route('tu.kelas.index') }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            &larr; Kembali ke Daftar Kelas
        </a>
    </div>

    <!-- Info Box -->
    <div class="custom-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-700 mb-2">
                    Tingkat {{ $kelas->tingkat }} &bull; {{ $kelas->jurusan ?? 'Umum' }}
                </span>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kelas->nama_kelas }}</h2>
                <p class="text-xs text-slate-500 mt-1">Tahun Ajaran: {{ $kelas->tahun_ajaran }}</p>
            </div>

            <div class="flex items-center space-x-6 text-xs text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-tight">Wali Kelas</span>
                    <strong class="text-slate-900 font-extrabold">{{ $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum Ditentukan' }}</strong>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <div>
                    <span class="text-slate-400 block text-[11px] font-bold uppercase tracking-tight">Jumlah Siswa</span>
                    <strong class="text-brand-600 font-extrabold">{{ $kelas->siswa->count() }} Orang</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="custom-card overflow-hidden">
        <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-tight">Daftar Siswa di Kelas Ini</h3>
            <a href="{{ route('tu.siswa.index', ['kelas_id' => $kelas->id]) }}" class="text-xs text-brand-600 hover:text-brand-700 font-bold">
                Kelola Siswa di Menu Siswa &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5 text-center w-12">Absen</th>
                        <th class="py-3 px-5">NIS</th>
                        <th class="py-3 px-5">Nama Lengkap</th>
                        <th class="py-3 px-5 text-center">L/P</th>
                        <th class="py-3 px-5">Kontak Orang Tua</th>
                        <th class="py-3 px-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelas->siswa as $siswa)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-5 text-center text-slate-400 font-bold">{{ $siswa->no_absen ?? '-' }}</td>
                            <td class="py-3 px-5 font-mono text-slate-600">{{ $siswa->nis }}</td>
                            <td class="py-3 px-5 font-bold text-slate-900">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-3 px-5 text-center font-bold text-slate-600">{{ $siswa->jenis_kelamin }}</td>
                            <td class="py-3 px-5 text-slate-500">{{ $siswa->no_telepon_ortu ?? '-' }}</td>
                            <td class="py-3 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
