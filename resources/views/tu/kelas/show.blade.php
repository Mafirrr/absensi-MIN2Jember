@extends('layouts.app')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas)
@section('page_title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">

    <div class="flex items-center space-x-2">
        <a href="{{ route('tu.kelas.index') }}" class="px-2.5 py-1.5 rounded-lg bg-white border border-stone-200 text-xs font-medium text-stone-700 hover:bg-stone-50">
            &larr; Kembali
        </a>
    </div>

    <!-- Info Box Minimalist -->
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-semibold text-stone-400 uppercase tracking-wider">Tingkat {{ $kelas->tingkat }} &bull; {{ $kelas->jurusan ?? 'Umum' }}</span>
                <h2 class="text-xl font-semibold text-stone-900 mt-0.5">{{ $kelas->nama_kelas }}</h2>
                <p class="text-xs text-stone-500 mt-1">Tahun Ajaran: {{ $kelas->tahun_ajaran }}</p>
            </div>

            <div class="flex items-center space-x-6 text-xs text-stone-700">
                <div>
                    <span class="text-stone-400 block text-[11px]">Wali Kelas</span>
                    <strong class="text-stone-900">{{ $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum Ditentukan' }}</strong>
                </div>
                <div class="h-6 w-px bg-stone-200"></div>
                <div>
                    <span class="text-stone-400 block text-[11px]">Jumlah Siswa</span>
                    <strong class="text-stone-900">{{ $kelas->siswa->count() }} Orang</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="p-3.5 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Daftar Siswa di Kelas Ini</h3>
            <a href="{{ route('tu.siswa.index', ['kelas_id' => $kelas->id]) }}" class="text-xs text-stone-600 hover:text-stone-900 font-medium">
                Kelola di Menu Siswa &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4 text-center w-12">Absen</th>
                        <th class="py-2.5 px-4">NIS</th>
                        <th class="py-2.5 px-4">Nama Lengkap</th>
                        <th class="py-2.5 px-4 text-center">L/P</th>
                        <th class="py-2.5 px-4">Kontak Orang Tua</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($kelas->siswa as $siswa)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2 px-4 text-center text-stone-400">{{ $siswa->no_absen ?? '-' }}</td>
                            <td class="py-2 px-4 font-mono text-stone-600">{{ $siswa->nis }}</td>
                            <td class="py-2 px-4 font-medium text-stone-900">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-2 px-4 text-center text-stone-600">{{ $siswa->jenis_kelamin }}</td>
                            <td class="py-2 px-4 text-stone-600">{{ $siswa->no_telepon_ortu ?? '-' }}</td>
                            <td class="py-2 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-stone-100 text-stone-700">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-stone-400">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
