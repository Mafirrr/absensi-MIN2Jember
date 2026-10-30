@extends('layouts.app')

@section('title', 'Kelola Guru')
@section('page_title', 'Kelola Data Tenaga Pendidik')

@section('content')
<div class="space-y-6">

    <!-- Header / Title -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Guru</h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Manajemen akun pendidik, nomor identitas guru (NIP), dan hak akses kelas.</p>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="custom-card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('tu.guru.index') }}" class="flex items-center space-x-2 w-full sm:w-80">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, NIP..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>
            @if($search)
                <a href="{{ route('tu.guru.index') }}" class="text-xs text-slate-400 hover:text-slate-700">✕</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahGuru').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-600/25 transition self-start sm:self-auto flex items-center space-x-1.5">
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
                        <th class="py-3 px-5">NIP</th>
                        <th class="py-3 px-5">Email Login</th>
                        <th class="py-3 px-5">Wali Kelas</th>
                        <th class="py-3 px-5">Kontak</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guruList as $guru)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-5 font-bold text-slate-900">{{ $guru->name }}</td>
                            <td class="py-3.5 px-5 font-mono text-slate-600">{{ $guru->nip ?? '-' }}</td>
                            <td class="py-3.5 px-5 text-brand-600 font-medium">{{ $guru->email }}</td>
                            <td class="py-3.5 px-5">
                                @if($guru->kelasWali->isNotEmpty())
                                    @foreach($guru->kelasWali as $kw)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 font-bold text-[10px] mr-1">
                                            Wali {{ $kw->nama_kelas }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-500">{{ $guru->phone ?? '-' }}</td>
                            <td class="py-3.5 px-5 text-right space-x-3">
                                <button type="button" onclick="editGuru({{ json_encode($guru) }})" class="text-amber-600 hover:text-amber-700 font-bold">Edit</button>
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
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-100 shadow-2xl text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Tambah Guru Baru</h3>
            <button onclick="document.getElementById('modalTambahGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.guru.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" required placeholder="Contoh: Siti Rahmawati, S.Pd"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIP</label>
                    <input type="text" name="nip" placeholder="Nomor Induk Pegawai"
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
                    <input type="email" name="email" required placeholder="email@sekolah.sch.id"
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
                <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:bg-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahGuru').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Guru -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-100 shadow-2xl text-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-sm font-bold text-slate-900">Edit Data Guru</h3>
            <button onclick="document.getElementById('modalEditGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
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

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditGuru').classList.add('hidden')"
                    class="px-4 py-2 text-slate-500 hover:text-slate-800 font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-md shadow-brand-600/25">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editGuru(guru) {
        document.getElementById('formEditGuru').action = `/tu/guru/${guru.id}`;
        document.getElementById('edit_guru_name').value = guru.name;
        document.getElementById('edit_guru_nip').value = guru.nip ?? '';
        document.getElementById('edit_guru_gender').value = guru.gender ?? 'L';
        document.getElementById('edit_guru_email').value = guru.email;
        document.getElementById('edit_guru_phone').value = guru.phone ?? '';
        document.getElementById('modalEditGuru').classList.remove('hidden');
    }
</script>
@endpush
