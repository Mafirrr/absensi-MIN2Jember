@extends('layouts.app')

@section('title', 'Input Absensi')
@section('page_title', 'Input Absensi Siswa')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="bg-white border border-stone-200 p-6 rounded-xl text-stone-600 text-xs">
            <p class="font-semibold text-stone-900 mb-1">Akses Dibatasi:</p>
            <p>Anda belum memiliki kelas yang ditugaskan untuk diinput absensinya. Hubungi pihak Tata Usaha.</p>
        </div>
    @else

        <!-- Controls Bar -->
        <div class="bg-white p-4 rounded-xl border border-stone-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form method="GET" action="{{ route('guru.absensi.index') }}" class="flex flex-wrap items-center gap-3">
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[10px] font-medium text-stone-500 mb-1">Kelas</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <div>
                            <span class="block text-[10px] font-medium text-stone-500 mb-1">Kelas</span>
                            <span class="inline-block px-2.5 py-1.5 rounded-lg bg-stone-100 text-stone-800 font-semibold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-medium text-stone-500 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                            class="px-2.5 py-1.5 bg-stone-50 border border-stone-300 rounded-lg text-xs text-stone-800">
                    </div>
                </form>

                <div class="flex items-center space-x-2 pt-2 md:pt-0">
                    <button type="button" onclick="markAll('hadir')"
                        class="px-3 py-1.5 rounded-lg border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 text-xs font-medium transition">
                        Tandai Semua Hadir
                    </button>

                    <button type="submit" form="formAbsensi"
                        class="px-4 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition">
                        Simpan Absensi
                    </button>
                </div>
            </div>
        </div>

        @if($existingAbsensi->isNotEmpty())
            <div class="p-3 rounded-lg bg-stone-100 border border-stone-200 text-stone-700 text-xs flex items-center justify-between">
                <span>Data absensi tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong> sudah tercatat. Simpan ulang untuk memperbarui.</span>
                <span class="text-[10px] uppercase font-semibold text-stone-500 tracking-wider">Tersimpan</span>
            </div>
        @endif

        <!-- Form Table -->
        <form method="POST" action="{{ route('guru.absensi.store') }}" id="formAbsensi">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
                <div class="p-3.5 bg-stone-50/70 border-b border-stone-200 flex items-center justify-between">
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">
                        Daftar Siswa ({{ $siswaList->count() }})
                    </h3>
                    <span class="text-[11px] text-stone-400">Pilih status kehadiran</span>
                </div>

                <div class="divide-y divide-stone-100">
                    @forelse($siswaList as $idx => $siswa)
                        @php
                            $currentStatus = old("absensi.{$siswa->id}.status", $existingAbsensi[$siswa->id]->status ?? 'hadir');
                            $currentCatatan = old("absensi.{$siswa->id}.catatan", $existingAbsensi[$siswa->id]->catatan ?? '');
                        @endphp
                        <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-stone-50/40">
                            <!-- Siswa Info -->
                            <div class="flex items-center space-x-3 min-w-[200px]">
                                <span class="w-6 text-center text-xs text-stone-400 font-medium">
                                    {{ $siswa->no_absen ?? ($idx + 1) }}
                                </span>
                                <div>
                                    <p class="text-xs font-medium text-stone-900">{{ $siswa->nama_lengkap }}</p>
                                    <span class="text-[11px] text-stone-400 font-mono">NIS: {{ $siswa->nis }}</span>
                                </div>
                            </div>

                            <!-- Radio Selectors Minimalist -->
                            <div class="flex items-center space-x-1.5">
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="hadir"
                                        class="peer sr-only status-hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}>
                                    <div class="px-2.5 py-1 rounded text-xs font-medium border border-stone-200 text-stone-600 peer-checked:bg-stone-900 peer-checked:text-white peer-checked:border-stone-900 hover:bg-stone-50 transition">
                                        Hadir
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="izin"
                                        class="peer sr-only status-izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                    <div class="px-2.5 py-1 rounded text-xs font-medium border border-stone-200 text-stone-600 peer-checked:bg-stone-900 peer-checked:text-white peer-checked:border-stone-900 hover:bg-stone-50 transition">
                                        Izin
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="sakit"
                                        class="peer sr-only status-sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                    <div class="px-2.5 py-1 rounded text-xs font-medium border border-stone-200 text-stone-600 peer-checked:bg-stone-900 peer-checked:text-white peer-checked:border-stone-900 hover:bg-stone-50 transition">
                                        Sakit
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="alpa"
                                        class="peer sr-only status-alpa" {{ $currentStatus == 'alpa' ? 'checked' : '' }}>
                                    <div class="px-2.5 py-1 rounded text-xs font-medium border border-stone-200 text-stone-600 peer-checked:bg-stone-900 peer-checked:text-white peer-checked:border-stone-900 hover:bg-stone-50 transition">
                                        Alpa
                                    </div>
                                </label>
                            </div>

                            <!-- Catatan -->
                            <div class="w-full sm:w-52">
                                <input type="text" name="absensi[{{ $siswa->id }}][catatan]" value="{{ $currentCatatan }}"
                                    placeholder="Keterangan opsional"
                                    class="w-full px-2.5 py-1 bg-white border border-stone-200 rounded text-xs text-stone-800 placeholder-stone-400 focus:outline-none focus:border-stone-900">
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-stone-400">
                            Tidak ada siswa di kelas ini.
                        </div>
                    @endforelse
                </div>

                @if($siswaList->isNotEmpty())
                    <div class="p-3 bg-stone-50 border-t border-stone-200 flex justify-end">
                        <button type="submit"
                            class="px-4 py-1.5 rounded-lg bg-stone-900 hover:bg-stone-800 text-white text-xs font-medium transition">
                            Simpan Perubahan
                        </button>
                    </div>
                @endif
            </div>
        </form>

    @endif

</div>
@endsection

@push('scripts')
<script>
    function markAll(status) {
        document.querySelectorAll(`.status-${status}`).forEach(r => r.checked = true);
    }
</script>
@endpush
