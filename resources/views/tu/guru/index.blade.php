@extends('layouts.app')

@section('title', 'Kelola Guru')
@section('page_title', 'Kelola Data Tenaga Pendidik (Guru)')

@section('content')
<div class="space-y-6">

    <!-- Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('tu.guru.index') }}" class="flex items-center space-x-2 w-full sm:w-80">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama guru, email, atau NIP..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if($search)
                <a href="{{ route('tu.guru.index') }}" class="p-2 text-slate-400 hover:text-slate-600">✕</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahGuru').classList.remove('hidden')"
            class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Guru Baru</span>
        </button>
    </div>

    <!-- Table Guru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-6">Nama Guru</th>
                        <th class="py-3 px-6">NIP</th>
                        <th class="py-3 px-6">Email Akun</th>
                        <th class="py-3 px-6">Wali Kelas</th>
                        <th class="py-3 px-6">No Telepon</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($guruList as $guru)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                        {{ substr($guru->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $guru->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-xs text-slate-600">
                                {{ $guru->nip ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-xs text-blue-600">
                                {{ $guru->email }}
                            </td>
                            <td class="py-3.5 px-6 text-xs">
                                @if($guru->kelasWali->isNotEmpty())
                                    @foreach($guru->kelasWali as $kw)
                                        <span class="inline-block px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold mr-1">
                                            Wali {{ $kw->nama_kelas }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400 italic">Bukan wali kelas</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-xs text-slate-600">
                                {{ $guru->phone ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <button type="button" onclick="editGuru({{ json_encode($guru) }})" title="Edit Guru" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('tu.guru.destroy', $guru->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru {{ $guru->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Guru" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-slate-100">
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
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada data guru yang terdaftar.
                            </td>
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
<div id="modalTambahGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Tambah Tenaga Pendidik (Guru)</h3>
            <button onclick="document.getElementById('modalTambahGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.guru.store') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" required placeholder="Contoh: Siti Rahmawati, S.Pd"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NIP</label>
                    <input type="text" name="nip" placeholder="Nomor Induk Pegawai"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Login *</label>
                    <input type="email" name="email" required placeholder="guru@sekolah.sch.id"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kata Sandi *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahGuru').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700">Simpan Guru</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Guru -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl animate-fade-in">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Edit Data Guru</h3>
            <button onclick="document.getElementById('modalEditGuru').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form method="POST" id="formEditGuru" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" id="edit_guru_name" required
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">NIP</label>
                    <input type="text" name="nip" id="edit_guru_nip"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jenis Kelamin</label>
                    <select name="gender" id="edit_guru_gender" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Login *</label>
                    <input type="email" name="email" id="edit_guru_email" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Ubah Sandi (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tetap"
                        class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP</label>
                <input type="text" name="phone" id="edit_guru_phone"
                    class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalEditGuru').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-amber-600 text-white hover:bg-amber-700">Perbarui Guru</button>
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
