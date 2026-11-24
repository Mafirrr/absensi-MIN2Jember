@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Siswa</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Data induk siswa terdaftar, NIS, rombel penempatan, dan status keaktifan.</p>
        </div>
    </div>

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

        <div class="flex items-center space-x-2 self-start md:self-auto flex-shrink-0">
            <a href="{{ route('tu.siswa.template') }}" class="px-3 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center space-x-1.5 shadow-2xs" title="Unduh Template Excel">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span class="hidden sm:inline">Template</span>
            </a>

            <button type="button" onclick="document.getElementById('modalBulkSiswaTu').classList.remove('hidden')"
                class="px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Tambah Sekaligus</span>
            </button>

            <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')"
                class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Siswa</span>
            </button>
        </div>
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

<!-- Modal Tambah Sekaligus / Bulk Import (TU) -->
<div id="modalBulkSiswaTu" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Siswa Sekaligus (Bulk / Import)</h3>
                <p class="text-[11px] text-slate-500">Pilih rombel kelas dan masukkan data siswa secara massal.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalBulkSiswaTu').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-base font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('tu.siswa.bulk') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Rombel Kelas Tujuan *</label>
                <select name="kelas_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Pilih Rombel Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                            Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tab Tipe Input -->
            <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
                <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="radio" name="tipe_input" value="text" checked onchange="toggleTuBulkType('text')" class="text-emerald-600 focus:ring-emerald-500">
                    <span>Salin / Tempel Teks (Paste dari Excel)</span>
                </label>
                <span class="text-slate-300">|</span>
                <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="radio" name="tipe_input" value="file" onchange="toggleTuBulkType('file')" class="text-emerald-600 focus:ring-emerald-500">
                    <span>Upload File Excel (.xlsx / .csv)</span>
                </label>
            </div>

            <!-- Bagian Text Paste -->
            <div id="tuSectionText" class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Tempel Baris Siswa *</label>
                    <span class="text-[10px] text-slate-400">Pemisah: Koma (,) atau Tab Excel</span>
                </div>
                <textarea name="raw_text" rows="6" placeholder="Contoh format per baris:
1001, 00151001, Ahmad Dani Pratama, L, 081234567890
1002, 00151002, Aisyah Putri Azzahra, P, 081234567891
1003, 00151003, Bilal Habiburrahman, L, 081234567892" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-brand-500"></textarea>
                <p class="text-[10px] text-slate-500">
                    💡 <em>Tip: Anda bisa langsung memblok & menyalin (copy-paste) kolom NIS, NISN, Nama, JK, No HP dari lembar Excel Anda ke dalam kotak ini.</em>
                </p>
            </div>

            <!-- Bagian Upload File -->
            <div id="tuSectionFile" class="space-y-2 hidden">
                <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilih File Excel / CSV *</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] text-slate-500">Format kolom: NIS, NISN, Nama Lengkap, JK (L/P), No HP Ortu</span>
                    <a href="{{ route('tu.siswa.template') }}" class="text-[11px] text-brand-600 hover:underline font-bold">Unduh Template &rarr;</a>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalBulkSiswaTu').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition">Mulai Tambah Sekaligus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTuBulkType(type) {
        if (type === 'file') {
            document.getElementById('tuSectionFile').classList.remove('hidden');
            document.getElementById('tuSectionText').classList.add('hidden');
        } else {
            document.getElementById('tuSectionFile').classList.add('hidden');
            document.getElementById('tuSectionText').classList.remove('hidden');
        }
    }
</script>

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
