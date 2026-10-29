@extends('layouts.app')

@section('title', 'Kelola Guru')
@section('page_title', 'Kelola Data Guru')

@section('content')
<div class="space-y-6">

    <!-- Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-xl border border-stone-200">
        <form method="GET" action="{{ route('tu.guru.index') }}" class="flex items-center space-x-2 w-full sm:w-72">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, NIP..."
                class="w-full px-3 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-stone-900">
            @if($search)
                <a href="{{ route('tu.guru.index') }}" class="text-xs text-stone-400 hover:text-stone-700">✕</a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahGuru').classList.remove('hidden')"
            class="px-3.5 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition self-start sm:self-auto">
            + Tambah Guru
        </button>
    </div>

    <!-- Table Guru -->
    <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-stone-50/70 border-b border-stone-200 text-stone-500 font-medium">
                        <th class="py-2.5 px-4">Nama Guru</th>
                        <th class="py-2.5 px-4">NIP</th>
                        <th class="py-2.5 px-4">Email Login</th>
                        <th class="py-2.5 px-4">Wali Kelas</th>
                        <th class="py-2.5 px-4">Kontak</th>
                        <th class="py-2.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($guruList as $guru)
                        <tr class="hover:bg-stone-50/50">
                            <td class="py-2.5 px-4 font-semibold text-stone-900">{{ $guru->name }}</td>
                            <td class="py-2.5 px-4 font-mono text-stone-600">{{ $guru->nip ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-stone-600">{{ $guru->email }}</td>
                            <td class="py-2.5 px-4">
                                @if($guru->kelasWali->isNotEmpty())
                                    @foreach($guru->kelasWali as $kw)
                                        <span class="inline-block px-2 py-0.5 rounded bg-stone-100 text-stone-800 font-medium mr-1">
                                            Wali {{ $kw->nama_kelas }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-stone-600">{{ $guru->phone ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-right space-x-2">
                                <button type="button" onclick="editGuru({{ json_encode($guru) }})" class="text-stone-600 hover:text-stone-900">Edit</button>
                                <form method="POST" action="{{ route('tu.guru.destroy', $guru->id) }}" onsubmit="return confirm('Hapus data guru {{ $guru->name }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-stone-400">Belum ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guruList->hasPages())
            <div class="p-3 border-t border-stone-100">
                {{ $guruList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Guru -->
<div id="modalTambahGuru" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-5 border border-stone-200 shadow-lg text-xs">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="font-semibold uppercase tracking-wider text-stone-900">Tambah Guru Baru</h3>
            <button onclick="document.getElementById('modalTambahGuru').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" action="{{ route('tu.guru.store') }}" class="space-y-3">
            @csrf

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" required placeholder="Contoh: Siti Rahmawati, S.Pd"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NIP</label>
                    <input type="text" name="nip" placeholder="Nomor Induk Pegawai"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Email *</label>
                    <input type="email" name="email" required placeholder="email@sekolah.sch.id"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Kata Sandi *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Kontak Telepon / WA</label>
                <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalTambahGuru').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Guru -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-stone-900/40 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-xl max-w-md w-full p-5 border border-stone-200 shadow-lg text-xs">
        <div class="flex items-center justify-between pb-3 border-b border-stone-100 mb-4">
            <h3 class="font-semibold uppercase tracking-wider text-stone-900">Edit Data Guru</h3>
            <button onclick="document.getElementById('modalEditGuru').classList.add('hidden')" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>

        <form method="POST" id="formEditGuru" action="" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-stone-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="name" id="edit_guru_name" required
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">NIP</label>
                    <input type="text" name="nip" id="edit_guru_nip"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" id="edit_guru_gender" class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Email *</label>
                    <input type="email" name="email" id="edit_guru_email" required
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
                <div>
                    <label class="block font-medium text-stone-700 mb-1">Ubah Sandi (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tetap"
                        class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
                </div>
            </div>

            <div>
                <label class="block font-medium text-stone-700 mb-1">Kontak Telepon</label>
                <input type="text" name="phone" id="edit_guru_phone"
                    class="w-full px-2.5 py-1.5 border border-stone-300 rounded-lg focus:outline-none focus:border-stone-900">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('modalEditGuru').classList.add('hidden')"
                    class="px-3 py-1.5 text-stone-600 hover:text-stone-900 font-medium">Batal</button>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white font-medium">Perbarui</button>
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
