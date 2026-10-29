@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="custom-card p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('tu.siswa.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative w-full sm:w-60">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIS..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                        Kelas {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="lulus" {{ $status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="pindah" {{ $status == 'pindah' ? 'selected' : '' }}>Pindah</option>
            </select>

            @if($search || $kelasId || $status)
                <a href="{{ route('tu.siswa.index') }}" class="text-xs text-slate-400 hover:text-slate-700 ml-1 font-bold">Reset</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition self-start md:self-auto flex items-center space-x-1.5 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Siswa</span>
        </button>
    </div>

    <!-- Table Siswa -->
    <div class="custom-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5">NIS</th>
                        <th class="py-3 px-5">Nama Siswa</th>
                        <th class="py-3 px-5">Kelas</th>
                        <th class="py-3 px-5 text-center">L/P</th>
                        <th class="py-3 px-5">Kontak Ortu</th>
                        <th class="py-3 px-5 text-center">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $siswa)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-5 font-mono text-slate-600">{{ $siswa->nis }}</td>
                            <td class="py-3 px-5 font-bold text-slate-900">
                                {{ $siswa->nama_lengkap }}
                                @if($siswa->no_absen)
                                    <span class="text-slate-400 font-normal ml-1">({{ $siswa->no_absen }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-slate-700 font-bold">
                                {{ $siswa->kelas ? $siswa->kelas->nama_kelas : '-' }}
                            </td>
                            <td class="py-3 px-5 text-center font-bold text-slate-600">{{ $siswa->jenis_kelamin }}</td>
                            <td class="py-3 px-5 text-slate-500">{{ $siswa->no_telepon_ortu ?? '-' }}</td>
                            <td class="py-3 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-right space-x-3">
                                <button type="button" onclick="editSiswa({{ json_encode($siswa) }})" class="text-amber-600 hover:text-amber-700 font-bold">Edit</button>
                                <form method="POST" action="{{ route('tu.siswa.destroy', $siswa->id) }}" onsubmit="return confirm('Hapus siswa {{ $siswa->nama_lengkap }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswaList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $siswaList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Siswa -->
<div id="modalTambahSiswa" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-100 shadow-2xl max-h-[90vh] overflow-y-auto text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Tambah Siswa Baru</h3>
            <button onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.siswa.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIS *</label>
                    <input type="text" name="nis" required placeholder="Contoh: 1025"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NISN</label>
                    <input type="text" name="nisn" placeholder="Nomor NISN"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama siswa"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kelas *</label>
                    <select name="kelas_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" min="1" placeholder="Contoh: 1"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kontak Orang Tua</label>
                <input type="text" name="no_telepon_ortu" placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat siswa..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-100 shadow-2xl max-h-[90vh] overflow-y-auto text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Edit Data Siswa</h3>
            <button onclick="document.getElementById('modalEditSiswa').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" id="formEditSiswa" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIS *</label>
                    <input type="text" name="nis" id="edit_nis" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kelas *</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jenis_kelamin" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" id="edit_no_absen" min="1"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status</label>
                    <select name="status" id="edit_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kontak Orang Tua</label>
                <input type="text" name="no_telepon_ortu" id="edit_no_telepon_ortu"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" id="edit_alamat" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditSiswa').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Perbarui</button>
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
