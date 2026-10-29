@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('tu.siswa.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIS, atau NISN..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Filter Kelas -->
            <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                        Kelas {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Status -->
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                <option value="">-- Semua Status --</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="lulus" {{ $status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="pindah" {{ $status == 'pindah' ? 'selected' : '' }}>Pindah</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                Filter
            </button>

            @if($search || $kelasId || $status)
                <a href="{{ route('tu.siswa.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <!-- Tambah Siswa Button -->
        <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')"
            class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Siswa Baru</span>
        </button>
    </div>

    <!-- Table Siswa -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6">NIS / NISN</th>
                        <th class="py-3 px-6">Nama Siswa</th>
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6 text-center">L/P</th>
                        <th class="py-3 px-6">Kontak Ortu</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $siswa)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-6">
                                <span class="block font-bold text-slate-800 text-xs font-mono">{{ $siswa->nis }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $siswa->nisn ?? 'NISN -' }}</span>
                            </td>
                            <td class="py-3 px-6 font-semibold text-slate-800">
                                {{ $siswa->nama_lengkap }}
                                @if($siswa->no_absen)
                                    <span class="text-xs text-slate-400 ml-1">(Absen: {{ $siswa->no_absen }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-xs font-medium text-slate-700">
                                <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $siswa->kelas ? $siswa->kelas->nama_kelas : '-' }}
                                </span>
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
                                @if($siswa->status == 'aktif')
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">{{ ucfirst($siswa->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <button type="button" onclick="editSiswa({{ json_encode($siswa) }})" title="Edit Siswa" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('tu.siswa.destroy', $siswa->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $siswa->nama_lengkap }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Siswa" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-slate-100">
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
                                Tidak ada data siswa yang sesuai.
                            </td>
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
<div id="modalTambahSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl animate-fade-in max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Tambah Siswa Baru</h3>
            <button onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.siswa.store') }}" class="mt-4 space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NIS *</label>
                    <input type="text" name="nis" required placeholder="Contoh: 1025"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NISN</label>
                    <input type="text" name="nisn" placeholder="Contoh: 0089123456"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama lengkap siswa"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kelas *</label>
                    <select name="kelas_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" min="1" placeholder="Contoh: 1"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Siswa</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">No HP / WhatsApp Orang Tua</label>
                <input type="text" name="no_telepon_ortu" placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Alamat Tempat Tinggal</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..."
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Siswa -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl animate-fade-in max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Edit Data Siswa</h3>
            <button onclick="document.getElementById('modalEditSiswa').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" id="formEditSiswa" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NIS *</label>
                    <input type="text" name="nis" id="edit_nis" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kelas *</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jenis_kelamin" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nomor Absen</label>
                    <input type="number" name="no_absen" id="edit_no_absen" min="1"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Siswa</label>
                    <select name="status" id="edit_status" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">No HP / WhatsApp Orang Tua</label>
                <input type="text" name="no_telepon_ortu" id="edit_no_telepon_ortu"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Alamat Tempat Tinggal</label>
                <textarea name="alamat" id="edit_alamat" rows="2"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditSiswa').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-amber-600 text-white hover:bg-amber-700">Perbarui Siswa</button>
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
