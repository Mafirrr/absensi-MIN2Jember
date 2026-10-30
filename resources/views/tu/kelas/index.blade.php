@extends('layouts.app')

@section('title', 'Kelola Kelas')
@section('page_title', 'Kelola Rombel Kelas')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Kelas</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Manajemen rombongan belajar, tingkat kelas, jurusan, dan penugasan wali kelas.</p>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="custom-card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('tu.kelas.index') }}" class="flex items-center space-x-2 w-full sm:w-80">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas atau wali..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>
            @if($search)
                <a href="{{ route('tu.kelas.index') }}" class="text-xs text-slate-400 hover:text-slate-700">✕</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition self-start sm:self-auto flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Kelas</span>
        </button>
    </div>

    <!-- Table -->
    <div class="custom-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5">Nama Kelas</th>
                        <th class="py-3 px-5">Tingkat</th>
                        <th class="py-3 px-5">Jurusan</th>
                        <th class="py-3 px-5">Wali Kelas</th>
                        <th class="py-3 px-5 text-center">Jumlah Siswa</th>
                        <th class="py-3 px-5">Tahun Ajaran</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelasList as $kelas)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5 font-bold text-slate-900">
                                <a href="{{ route('tu.kelas.show', $kelas->id) }}" class="text-brand-600 hover:underline">
                                    {{ $kelas->nama_kelas }}
                                </a>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600 font-medium">Kelas {{ $kelas->tingkat }}</td>
                            <td class="py-3.5 px-5 text-slate-500">{{ $kelas->jurusan ?? '-' }}</td>
                            <td class="py-3.5 px-5 font-bold text-slate-800">
                                {{ $kelas->waliKelas ? $kelas->waliKelas->name : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-[11px]">
                                    {{ $kelas->siswa_count }} Siswa
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-500">{{ $kelas->tahun_ajaran }}</td>
                            <td class="py-3.5 px-5 text-right space-x-3">
                                <a href="{{ route('tu.kelas.show', $kelas->id) }}" class="text-brand-600 hover:text-brand-700 font-bold">Lihat</a>
                                <button type="button" onclick="editKelas({{ json_encode($kelas) }})" class="text-amber-600 hover:text-amber-700 font-bold">Edit</button>
                                <form method="POST" action="{{ route('tu.kelas.destroy', $kelas->id) }}" onsubmit="return confirm('Hapus kelas {{ $kelas->nama_kelas }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data kelas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kelasList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kelasList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Kelas -->
<div id="modalTambahKelas" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-100 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Tambah Kelas Baru</h3>
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.kelas.store') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" required placeholder="Contoh: X RPL 1"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tingkat *</label>
                    <select name="tingkat" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" placeholder="RPL, TKJ, dll"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="2025/2026" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modalEditKelas" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-100 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Edit Data Kelas</h3>
            <button onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" id="formEditKelas" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" id="edit_nama_kelas" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tingkat *</label>
                    <select name="tingkat" id="edit_tingkat" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" id="edit_jurusan"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" id="edit_wali_kelas_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editKelas(kelas) {
        document.getElementById('formEditKelas').action = `/tu/kelas/${kelas.id}`;
        document.getElementById('edit_nama_kelas').value = kelas.nama_kelas;
        document.getElementById('edit_tingkat').value = kelas.tingkat;
        document.getElementById('edit_jurusan').value = kelas.jurusan ?? '';
        document.getElementById('edit_wali_kelas_id').value = kelas.wali_kelas_id ?? '';
        document.getElementById('edit_tahun_ajaran').value = kelas.tahun_ajaran;
        document.getElementById('modalEditKelas').classList.remove('hidden');
    }
</script>
@endpush
