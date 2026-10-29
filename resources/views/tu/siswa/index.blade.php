@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-xl border border-stone-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('tu.siswa.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIS..."
                class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-stone-900 w-44">

            <select name="kelas_id" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                        Kelas {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="lulus" {{ $status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="pindah" {{ $status == 'pindah' ? 'selected' : '' }}>Pindah</option>
            </select>

            @if($search || $kelasId || $status)
                <a href="{{ route('tu.siswa.index') }}" class="text-xs text-stone-400 hover:text-stone-700 ml-1">Reset</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')"
            class="px-3.5 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition self-start md:self-auto flex-shrink-0">
            + Tambah Siswa
        </button>
    </div>

    <!-- Table Siswa -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4">NIS</th>
                        <th class="py-2.5 px-4">Nama Siswa</th>
                        <th class="py-2.5 px-4">Kelas</th>
                        <th class="py-2.5 px-4 text-center">L/P</th>
                        <th class="py-2.5 px-4">Kontak Ortu</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($siswaList as $siswa)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2.5 px-4 font-mono text-stone-600">{{ $siswa->nis }}</td>
                            <td class="py-2.5 px-4 font-medium text-stone-900">
                                {{ $siswa->nama_lengkap }}
                                @if($siswa->no_absen)
                                    <span class="text-stone-400 ml-1">({{ $siswa->no_absen }})</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-stone-700">
                                {{ $siswa->kelas ? $siswa->kelas->nama_kelas : '-' }}
                            </td>
                            <td class="py-2.5 px-4 text-center text-stone-600">{{ $siswa->jenis_kelamin }}</td>
                            <td class="py-2.5 px-4 text-stone-600">{{ $siswa->no_telepon_ortu ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-stone-100 text-stone-700">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 text-right space-x-2">
                                <button type="button" onclick="editSiswa({{ json_encode($siswa) }})" class="text-stone-600 hover:text-stone-900">Edit</button>
                                <form method="POST" action="{{ route('tu.siswa.destroy', $siswa->id) }}" onsubmit="return confirm('Hapus siswa {{ $siswa->nama_lengkap }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-stone-400">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswaList->hasPages())
            <div class="p-3 border-t border-stone-100">
                {{ $siswaList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Siswa -->
<div id="modalTambahSiswa" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-lg w-full p-5 border border-stone-200 shadow-lg max-h-[90vh] overflow-y-auto text-xs">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="font-semibold uppercase tracking-wider text-stone-900">Tambah Siswa Baru</h3>
            <button onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.siswa.store') }}" class="space-y-3">
            @csrf

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NIS *</label>
                    <input type="text" name="nis" required placeholder="Contoh: 1025"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NISN</label>
                    <input type="text" name="nisn" placeholder="Nomor NISN"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama siswa"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Kelas *</label>
                    <select name="kelas_id" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" min="1" placeholder="Contoh: 1"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Status</label>
                    <select name="status" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Kontak Orang Tua</label>
                <input type="text" name="no_telepon_ortu" placeholder="08xxxxxxxxxx"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat siswa..."
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-lg w-full p-5 border border-stone-200 shadow-lg max-h-[90vh] overflow-y-auto text-xs">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="font-semibold uppercase tracking-wider text-stone-900">Edit Data Siswa</h3>
            <button onclick="document.getElementById('modalEditSiswa').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" id="formEditSiswa" action="" class="space-y-3">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NIS *</label>
                    <input type="text" name="nis" id="edit_nis" required
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Kelas *</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jenis_kelamin" required class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" id="edit_no_absen" min="1"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Status</label>
                    <select name="status" id="edit_status" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Kontak Orang Tua</label>
                <input type="text" name="no_telepon_ortu" id="edit_no_telepon_ortu"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Alamat</label>
                <textarea name="alamat" id="edit_alamat" rows="2"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalEditSiswa').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editSiswa(siswa) {
        document.getElementById('formEditSiswa').action = `/tu/siswa/${siswa.id}`;
        document.getElementById('edit_nis').value = siswa.nis;
        document.getElementById('edit_nisn').value = siswa.nisn ?? '';
        document.getElementById('edit_nama_lengkap').value = siswa.nama_lengkap;
        document.getElementById('edit_kelas_id').value = siswa.kelas_id;
        document.getElementById('edit_jenis_kelamin').value = siswa.jenis_kelamin;
        document.getElementById('edit_no_absen').value = siswa.no_absen ?? '';
        document.getElementById('edit_status').value = siswa.status;
        document.getElementById('edit_no_telepon_ortu').value = siswa.no_telepon_ortu ?? '';
        document.getElementById('edit_alamat').value = siswa.alamat ?? '';
        document.getElementById('modalEditSiswa').classList.remove('hidden');
    }
</script>
@endpush
