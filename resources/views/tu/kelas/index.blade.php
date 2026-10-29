@extends('layouts.app')

@section('title', 'Kelola Kelas')
@section('page_title', 'Kelola Data Rombel Kelas')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <!-- Search -->
        <form method="GET" action="{{ route('tu.kelas.index') }}" class="flex items-center space-x-2 w-full sm:w-80">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas, tingkat, wali kelas..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if($search)
                <a href="{{ route('tu.kelas.index') }}" class="p-2 text-slate-400 hover:text-slate-600">✕</a>
            @endif
        </form>

        <!-- Tambah Kelas Button -->
        <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')"
            class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Kelas Baru</span>
        </button>
    </div>

    <!-- Table of Classes -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6">Nama Kelas</th>
                        <th class="py-3 px-6">Tingkat</th>
                        <th class="py-3 px-6">Jurusan / Peminatan</th>
                        <th class="py-3 px-6">Wali Kelas</th>
                        <th class="py-3 px-6 text-center">Jumlah Siswa</th>
                        <th class="py-3 px-6">Tahun Ajaran</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelasList as $kelas)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-6 font-bold text-slate-800">
                                <a href="{{ route('tu.kelas.show', $kelas->id) }}" class="text-blue-600 hover:underline">
                                    {{ $kelas->nama_kelas }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6 text-slate-600 text-xs">
                                Kelas {{ $kelas->tingkat }}
                            </td>
                            <td class="py-3.5 px-6 text-slate-600 text-xs">
                                {{ $kelas->jurusan ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-slate-800 text-xs font-medium">
                                @if($kelas->waliKelas)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>{{ $kelas->waliKelas->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-center font-semibold text-slate-700">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-xs">
                                    {{ $kelas->siswa_count }} Siswa
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 text-xs">
                                {{ $kelas->tahun_ajaran }}
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('tu.kelas.show', $kelas->id) }}" title="Lihat Siswa" class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <button type="button" onclick="editKelas({{ json_encode($kelas) }})" title="Edit Kelas" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('tu.kelas.destroy', $kelas->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $kelas->nama_kelas }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Kelas" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-slate-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada kelas yang sesuai dengan pencarian.
                            </td>
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
<div id="modalTambahKelas" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Tambah Rombongan Belajar (Kelas)</h3>
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.kelas.store') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" placeholder="Contoh: X RPL 1, XI MIPA 2" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Tingkat *</label>
                    <select name="tingkat" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jurusan / Bidang</label>
                    <input type="text" name="jurusan" placeholder="Contoh: RPL, TKJ, MIPA"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Guru / Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }} (NIP: {{ $guru->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="2025/2026" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modalEditKelas" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Edit Data Kelas</h3>
            <button onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" id="formEditKelas" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" id="edit_nama_kelas" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Tingkat *</label>
                    <select name="tingkat" id="edit_tingkat" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jurusan / Bidang</label>
                    <input type="text" name="jurusan" id="edit_jurusan"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" id="edit_wali_kelas_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Guru / Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-amber-600 text-white hover:bg-amber-700">Perbarui Kelas</button>
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
