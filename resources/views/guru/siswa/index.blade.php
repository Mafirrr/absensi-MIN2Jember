@extends('layouts.app')

@section('title', 'Data Siswa Kelas')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Siswa Kelas</h2>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Kelola daftar siswa di rombel Anda. Siswa yang ditambahkan di sini <strong>langsung otomatis terhubung dan tampil di role Tata Usaha (TU)</strong>.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('guru.siswa.template') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Unduh Template Excel</span>
            </a>
        </div>
    </div>

    <!-- Filters & Action Buttons -->
    <div class="custom-card p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('guru.siswa.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            @if($kelasList->count() > 1)
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Pilih Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="relative w-full sm:w-60">
                @if($kelasList->count() > 1)
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
                @endif
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Cari Siswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama atau NIS..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </div>

            @if($search)
                <div class="self-end pb-1">
                    <a href="{{ route('guru.siswa.index', ['kelas_id' => $kelasId]) }}" class="text-xs text-slate-400 hover:text-slate-700 font-bold">Reset</a>
                </div>
            @endif
        </form>

        <div class="flex items-center space-x-2 self-start md:self-end">
            <!-- Tambah Sekaligus Button -->
            <button type="button" onclick="document.getElementById('modalBulkSiswaGuru').classList.remove('hidden')"
                class="px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Tambah Sekaligus / Import</span>
            </button>

            <!-- Tambah 1 Siswa Button -->
            <button type="button" onclick="document.getElementById('modalTambahSiswaGuru').classList.remove('hidden')"
                class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah 1 Siswa</span>
            </button>
        </div>
    </div>

    <!-- Info Rombel Card -->
    @if($selectedKelas)
        <div class="oripio-card p-4 bg-emerald-50/40 border border-emerald-100 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                    {{ $selectedKelas->tingkat }}
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Kelas {{ $selectedKelas->nama_kelas }}</h3>
                    <p class="text-[11px] text-slate-500">Tahun Ajaran: {{ $selectedKelas->tahun_ajaran }} &bull; Wali Kelas: <strong>{{ $selectedKelas->waliKelas->name ?? 'Belum Ditentukan' }}</strong></p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Terdaftar</span>
                <span class="text-base font-extrabold text-emerald-800">{{ $siswaList->total() }} Siswa</span>
            </div>
        </div>
    @endif

    <!-- Table Siswa -->
    <div class="custom-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5 text-center w-12">No</th>
                        <th class="py-3 px-5">NIS / NISN</th>
                        <th class="py-3 px-5">Nama Siswa</th>
                        <th class="py-3 px-5 text-center">L/P</th>
                        <th class="py-3 px-5">Kontak Orang Tua</th>
                        <th class="py-3 px-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswaList as $index => $siswa)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-5 text-center font-bold text-slate-500">
                                {{ $siswa->no_absen ?? ($index + 1) }}
                            </td>
                            <td class="py-3 px-5 font-mono text-slate-600">
                                <div>{{ $siswa->nis }}</div>
                                @if($siswa->nisn)
                                    <div class="text-[10px] text-slate-400">{{ $siswa->nisn }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-5 font-bold text-slate-900">
                                {{ $siswa->nama_lengkap }}
                            </td>
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
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-medium text-xs">Belum ada data siswa di kelas ini.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Gunakan tombol <strong>Tambah 1 Siswa</strong> atau <strong>Tambah Sekaligus</strong> untuk memasukkan data siswa.</p>
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

<!-- Modal Tambah 1 Siswa (Guru) -->
<div id="modalTambahSiswaGuru" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900">Tambah Siswa Baru ke Kelas {{ $selectedKelas ? $selectedKelas->nama_kelas : '' }}</h3>
            <button type="button" onclick="document.getElementById('modalTambahSiswaGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-base font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('guru.siswa.store') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas ? $selectedKelas->id : '' }}">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">NIS *</label>
                    <input type="text" name="nis" required placeholder="Contoh: 1025" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">NISN</label>
                    <input type="text" name="nisn" placeholder="Contoh: 00151025" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama lengkap siswa" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">No Absen</label>
                    <input type="number" name="no_absen" min="1" placeholder="Otomatis jika kosong" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">No. Kontak / HP Orang Tua</label>
                <input type="text" name="no_telepon_ortu" placeholder="08..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalTambahSiswaGuru').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Sekaligus / Bulk Import (Guru) -->
<div id="modalBulkSiswaGuru" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Tambah Siswa Sekaligus (Bulk / Import)</h3>
                <p class="text-[11px] text-slate-500">Rombel Tujuan: <strong>Kelas {{ $selectedKelas ? $selectedKelas->nama_kelas : '' }}</strong></p>
            </div>
            <button type="button" onclick="document.getElementById('modalBulkSiswaGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-base font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('guru.siswa.bulk') }}" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas ? $selectedKelas->id : '' }}">

            <!-- Tab Tipe Input -->
            <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
                <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="radio" name="tipe_input" value="text" checked onchange="toggleGuruBulkType('text')" class="text-emerald-600 focus:ring-emerald-500">
                    <span>Salin / Tempel Teks (Paste dari Excel)</span>
                </label>
                <span class="text-slate-300">|</span>
                <label class="flex items-center space-x-1.5 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="radio" name="tipe_input" value="file" onchange="toggleGuruBulkType('file')" class="text-emerald-600 focus:ring-emerald-500">
                    <span>Upload File Excel (.xlsx / .csv)</span>
                </label>
            </div>

            <!-- Bagian Text Paste -->
            <div id="guruSectionText" class="space-y-2">
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
            <div id="guruSectionFile" class="space-y-2 hidden">
                <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilih File Excel / CSV *</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-brand-500">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] text-slate-500">Format kolom: NIS, NISN, Nama Lengkap, JK (L/P), No HP Ortu</span>
                    <a href="{{ route('guru.siswa.template') }}" class="text-[11px] text-brand-600 hover:underline font-bold">Unduh Template &rarr;</a>
                </div>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-[11px]">
                ℹ️ <strong>Catatan:</strong> Siswa yang dimasukkan di sini akan langsung tersimpan di database dan otomatis terlihat di panel Tata Usaha (TU).
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalBulkSiswaGuru').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition">Mulai Tambah Sekaligus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleGuruBulkType(type) {
        if (type === 'file') {
            document.getElementById('guruSectionFile').classList.remove('hidden');
            document.getElementById('guruSectionText').classList.add('hidden');
        } else {
            document.getElementById('guruSectionFile').classList.add('hidden');
            document.getElementById('guruSectionText').classList.remove('hidden');
        }
    }
</script>
@endsection
