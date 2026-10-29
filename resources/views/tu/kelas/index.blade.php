@extends('layouts.app')

@section('title', 'Kelola Kelas')
@section('page_title', 'Kelola Data Kelas')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-xl border border-stone-200">
        <form method="GET" action="{{ route('tu.kelas.index') }}" class="flex items-center space-x-2 w-full sm:w-72">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas atau wali..."
                class="w-full px-3 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-stone-900">
            @if($search)
                <a href="{{ route('tu.kelas.index') }}" class="text-xs text-stone-400 hover:text-stone-700">✕</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')"
            class="px-3.5 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition self-start sm:self-auto">
            + Tambah Kelas
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4">Nama Kelas</th>
                        <th class="py-2.5 px-4">Tingkat</th>
                        <th class="py-2.5 px-4">Jurusan</th>
                        <th class="py-2.5 px-4">Wali Kelas</th>
                        <th class="py-2.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-2.5 px-4">Tahun Ajaran</th>
                        <th class="py-2.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($kelasList as $kelas)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2.5 px-4 font-semibold text-stone-900">
                                <a href="{{ route('tu.kelas.show', $kelas->id) }}" class="hover:underline">
                                    {{ $kelas->nama_kelas }}
                                </a>
                            </td>
                            <td class="py-2.5 px-4 text-stone-600">Kelas {{ $kelas->tingkat }}</td>
                            <td class="py-2.5 px-4 text-stone-600">{{ $kelas->jurusan ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-stone-800 font-medium">
                                {{ $kelas->waliKelas ? $kelas->waliKelas->name : '-' }}
                            </td>
                            <td class="py-2.5 px-4 text-center text-stone-700 font-medium">
                                {{ $kelas->siswa_count }}
                            </td>
                            <td class="py-2.5 px-4 text-stone-500">{{ $kelas->tahun_ajaran }}</td>
                            <td class="py-2.5 px-4 text-right space-x-2">
                                <a href="{{ route('tu.kelas.show', $kelas->id) }}" class="text-stone-600 hover:text-stone-900">Lihat</a>
                                <button type="button" onclick="editKelas({{ json_encode($kelas) }})" class="text-stone-600 hover:text-stone-900">Edit</button>
                                <form method="POST" action="{{ route('tu.kelas.destroy', $kelas->id) }}" onsubmit="return confirm('Hapus kelas {{ $kelas->nama_kelas }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-stone-400">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kelasList->hasPages())
            <div class="p-3 border-t border-stone-100">
                {{ $kelasList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Kelas -->
<div id="modalTambahKelas" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-5 border border-stone-200 shadow-lg">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-stone-900">Tambah Kelas</h3>
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.kelas.store') }}" class="space-y-3 text-xs">
            @csrf

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" required placeholder="Contoh: X RPL 1"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Tingkat *</label>
                    <select name="tingkat" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" placeholder="RPL, TKJ, dll"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="2025/2026" required
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modalEditKelas" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-5 border border-stone-200 shadow-lg">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-stone-900">Edit Data Kelas</h3>
            <button onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" id="formEditKelas" action="" class="space-y-3 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Kelas *</label>
                <input type="text" name="nama_kelas" id="edit_nama_kelas" required
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Tingkat *</label>
                    <select name="tingkat" id="edit_tingkat" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="10">Kelas 10 (X)</option>
                        <option value="11">Kelas 11 (XI)</option>
                        <option value="12">Kelas 12 (XII)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" id="edit_jurusan"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Wali Kelas</label>
                <select name="wali_kelas_id" id="edit_wali_kelas_id" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="edit_tahun_ajaran" required
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Perbarui</button>
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
