@extends('layouts.app')

@section('title', 'Kelola Guru')
@section('page_title', 'Kelola Data Tenaga Pendidik')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Tenaga Pendidik</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">
                Manajemen akun pendidik, nomor identitas guru (NIP), penugasan Guru Kelas (Wali) dan Guru Mata Pelajaran (Mapel).
            </p>
        </div>
    </div>

    <!-- Actions & Filter Bar -->
    <div class="custom-card p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('tu.guru.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, NIP, mapel..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <select name="jenis_guru" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                <option value="">Semua Jenis Guru</option>
                <option value="guru_kelas" {{ ($jenisGuru ?? '') == 'guru_kelas' ? 'selected' : '' }}>Guru Kelas (Wali)</option>
                <option value="guru_mapel" {{ ($jenisGuru ?? '') == 'guru_mapel' ? 'selected' : '' }}>Guru Mata Pelajaran (Mapel)</option>
            </select>

            @if($search || !empty($jenisGuru))
                <a href="{{ route('tu.guru.index') }}" class="text-xs text-slate-400 hover:text-slate-700 ml-1 font-bold">Reset</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahGuru').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition self-start md:self-auto flex items-center space-x-1.5 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Guru</span>
        </button>
    </div>

    <!-- Table Guru -->
    <div class="custom-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold">
                        <th class="py-3 px-5">Nama Guru</th>
                        <th class="py-3 px-5">Jenis & Mapel</th>
                        <th class="py-3 px-5">Penugasan Rombel</th>
                        <th class="py-3 px-5">Email Login</th>
                        <th class="py-3 px-5">Kontak</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guruList as $guru)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $guru->name }}</div>
                                <div class="font-mono text-[10px] text-slate-500">NIP: {{ $guru->nip ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($guru->isGuruMapel())
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-bold text-[10px]">
                                        Guru Mapel: {{ $guru->mata_pelajaran ?: 'Mata Pelajaran' }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-[10px]">
                                        Guru Kelas (Wali)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 max-w-xs">
                                @if($guru->isGuruKelas() && $guru->kelasWali->isNotEmpty())
                                    @foreach($guru->kelasWali as $kw)
                                        <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 font-bold text-[10px] mr-1 mb-1">
                                            Wali {{ $kw->nama_kelas }}
                                        </span>
                                    @endforeach
                                @elseif($guru->kelasAjar->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($guru->kelasAjar->take(4) as $ka)
                                            <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[10px]">
                                                {{ $ka->nama_kelas }}
                                            </span>
                                        @endforeach
                                        @if($guru->kelasAjar->count() > 4)
                                            <span class="text-[10px] text-slate-400 font-bold">+{{ $guru->kelasAjar->count() - 4 }} rombel</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Belum ada rombel</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-brand-600 font-medium">{{ $guru->email }}</td>
                            <td class="py-3.5 px-5 text-slate-500">{{ $guru->phone ?? '-' }}</td>
                            <td class="py-3.5 px-5 text-right space-x-3">
                                <button type="button" 
                                    data-guru="{{ json_encode([
                                        'id' => $guru->id,
                                        'name' => $guru->name,
                                        'nip' => $guru->nip,
                                        'gender' => $guru->gender,
                                        'email' => $guru->email,
                                        'phone' => $guru->phone,
                                        'jenis_guru' => $guru->jenis_guru ?? 'guru_kelas',
                                        'mata_pelajaran' => $guru->mata_pelajaran ?? '',
                                        'wali_kelas_id' => $guru->kelasWali->first()?->id ?? '',
                                    ]) }}"
                                    data-kelas-ajar="{{ json_encode($guru->kelasAjar->pluck('id')) }}"
                                    onclick="editGuruData(JSON.parse(this.dataset.guru), JSON.parse(this.dataset.kelasAjar))" 
                                    class="text-amber-600 hover:text-amber-700 font-bold">Edit</button>
                                <form method="POST" action="{{ route('tu.guru.destroy', $guru->id) }}" onsubmit="return confirm('Hapus data guru {{ $guru->name }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guruList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $guruList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Guru -->
<div id="modalTambahGuru" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-100 shadow-2xl text-xs max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Tambah Guru Baru</h3>
            <button onclick="document.getElementById('modalTambahGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('tu.guru.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Saroji, S.Pd.I"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Guru *</label>
                    <select name="jenis_guru" id="tambah_jenis_guru" onchange="toggleTambahJenisGuru(this.value)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                        <option value="guru_kelas">Guru Kelas (Wali)</option>
                        <option value="guru_mapel">Guru Mata Pelajaran (Mapel)</option>
                    </select>
                </div>
                <div id="wrapperTambahMapel">
                    <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Aqidah Akhlak / Fikih"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <!-- Pilihan Wali Kelas -->
            <div id="wrapperTambahWaliKelas">
                <label class="block font-bold text-slate-700 mb-1">Penugasan Wali Kelas (Jika Guru Kelas)</label>
                <select name="wali_kelas_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">-- Bukan Wali Kelas / Belum Ditugaskan --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@sekolah.sch.id"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kata Sandi *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kontak Telepon / WA</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <!-- Penugasan Kelas Ajar (Untuk Guru Mapel / Ajar) -->
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Penugasan Rombel Ajar (Opsional / Guru Mapel)</label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-36 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($kelasList as $k)
                        <label class="flex items-center space-x-1.5 text-[11px] text-slate-700 cursor-pointer">
                            <input type="checkbox" name="kelas_ajar[]" value="{{ $k->id }}" class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $k->nama_kelas }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahGuru').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Simpan Guru</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Guru -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-100 shadow-2xl text-xs max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Edit Data Guru</h3>
            <button onclick="document.getElementById('modalEditGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form method="POST" id="formEditGuru" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" id="edit_guru_name" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Guru *</label>
                    <select name="jenis_guru" id="edit_guru_jenis" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                        <option value="guru_kelas">Guru Kelas (Wali)</option>
                        <option value="guru_mapel">Guru Mata Pelajaran (Mapel)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="edit_guru_mapel" placeholder="Contoh: Aqidah Akhlak"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <!-- Edit Wali Kelas -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Penugasan Wali Kelas</label>
                <select name="wali_kelas_id" id="edit_guru_wali_kelas_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                    <option value="">-- Bukan Wali Kelas / Kosongkan --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIP</label>
                    <input type="text" name="nip" id="edit_guru_nip"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" id="edit_guru_gender" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email *</label>
                    <input type="email" name="email" id="edit_guru_email" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ubah Sandi (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tetap"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kontak Telepon</label>
                <input type="text" name="phone" id="edit_guru_phone"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <!-- Penugasan Kelas Ajar (Edit) -->
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Penugasan Rombel Ajar</label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-36 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($kelasList as $k)
                        <label class="flex items-center space-x-1.5 text-[11px] text-slate-700 cursor-pointer">
                            <input type="checkbox" name="kelas_ajar[]" value="{{ $k->id }}" id="edit_kelas_ajar_{{ $k->id }}" class="edit-kelas-checkbox rounded text-emerald-600 focus:ring-emerald-500">
                            <span>{{ $k->nama_kelas }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditGuru').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleTambahJenisGuru(val) {
        const mapelInput = document.querySelector('input[name="mata_pelajaran"]');
        if (val === 'guru_kelas') {
            mapelInput.value = 'Guru Kelas';
        } else {
            if (mapelInput.value === 'Guru Kelas') mapelInput.value = '';
        }
    }

    function editGuruData(guru, kelasAjarIds) {
        document.getElementById('formEditGuru').action = "{{ url('tu/guru') }}/" + guru.id;
        document.getElementById('edit_guru_name').value = guru.name || '';
        document.getElementById('edit_guru_nip').value = guru.nip || '';
        document.getElementById('edit_guru_gender').value = guru.gender || 'L';
        document.getElementById('edit_guru_email').value = guru.email || '';
        document.getElementById('edit_guru_phone').value = guru.phone || '';
        document.getElementById('edit_guru_jenis').value = guru.jenis_guru || 'guru_kelas';
        document.getElementById('edit_guru_mapel').value = guru.mata_pelajaran || '';
        document.getElementById('edit_guru_wali_kelas_id').value = guru.wali_kelas_id || '';

        // Reset check boxes
        document.querySelectorAll('.edit-kelas-checkbox').forEach(cb => cb.checked = false);
        if (Array.isArray(kelasAjarIds)) {
            kelasAjarIds.forEach(id => {
                const cb = document.getElementById(`edit_kelas_ajar_${id}`);
                if (cb) cb.checked = true;
            });
        }

        document.getElementById('modalEditGuru').classList.remove('hidden');
    }

    @if($errors->any() && !old('_method'))
        document.getElementById('modalTambahGuru').classList.remove('hidden');
    @endif
</script>
@endpush
