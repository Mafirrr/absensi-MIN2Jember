@extends('layouts.app')

@section('title', 'Input Absensi')
@section('page_title', 'Input Absensi Siswa')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="oripio-card p-10 text-center text-slate-500">
            <h3 class="text-base font-extrabold text-slate-800">Akses Dibatasi</h3>
            <p class="text-xs text-slate-500 mt-1">Anda belum memiliki rombel kelas yang ditugaskan untuk diinput absensinya.</p>
        </div>
    @else

        <!-- Header / Title -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Input Absensi Siswa</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Pencatatan presensi harian siswa untuk <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong>.</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white shadow-2xs text-xs font-semibold text-slate-600">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Controls Bar -->
        <div class="oripio-card p-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <form method="GET" action="{{ route('guru.absensi.index') }}" class="flex flex-wrap items-center gap-3">
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rombel Kelas</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
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
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rombel Kelas</span>
                            <span class="inline-block px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-100 font-extrabold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Absensi</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                            class="px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                </form>

                <div class="flex items-center space-x-2.5 pt-2 lg:pt-0">
                    <button type="button" onclick="markAll('hadir')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center space-x-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Tandai Semua Hadir</span>
                    </button>

                    <button type="submit" form="formAbsensi"
                        class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span>Simpan Absensi</span>
                    </button>
                </div>
            </div>
        </div>

        @if($existingAbsensi->isNotEmpty())
            <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 text-emerald-900 text-xs font-medium flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Data absensi tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong> sudah tercatat. Anda dapat memperbarui data ini kapan saja.</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-700 text-white font-extrabold text-[10px] uppercase tracking-wider">Mode Edit</span>
            </div>
        @endif

        <!-- Form Table -->
        <form method="POST" action="{{ route('guru.absensi.store') }}" id="formAbsensi">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="oripio-card overflow-hidden">
                <div class="p-4 bg-slate-50/70 border-b border-slate-100/80 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-800 tracking-tight">
                        Daftar Siswa Kelas {{ $selectedKelas->nama_kelas }} ({{ $siswaList->count() }} Siswa)
                    </h3>
                    <span class="text-xs text-slate-400">Pilih status kehadiran siswa</span>
                </div>

                <div class="divide-y divide-slate-100/80">
                    @forelse($siswaList as $idx => $siswa)
                        @php
                            $currentStatus = old("absensi.{$siswa->id}.status", $existingAbsensi[$siswa->id]->status ?? 'hadir');
                            $currentCatatan = old("absensi.{$siswa->id}.catatan", $existingAbsensi[$siswa->id]->catatan ?? '');
                        @endphp
                        <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                            <!-- Siswa Info -->
                            <div class="flex items-center space-x-3 min-w-[220px]">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ $siswa->no_absen ?? ($idx + 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $siswa->nama_lengkap }}</h4>
                                    <span class="text-[10px] text-slate-400 font-mono">NIS: {{ $siswa->nis }} &bull; {{ $siswa->jenis_kelamin == 'L' ? 'L' : 'P' }}</span>
                                </div>
                            </div>

                            <!-- Radio Selectors -->
                            <div class="flex items-center space-x-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="hadir"
                                        class="peer sr-only status-hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 hover:bg-slate-50 transition shadow-2xs">
                                        Hadir
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="izin"
                                        class="peer sr-only status-izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-50 transition shadow-2xs">
                                        Izin
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="sakit"
                                        class="peer sr-only status-sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 hover:bg-slate-50 transition shadow-2xs">
                                        Sakit
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="alpa"
                                        class="peer sr-only status-alpa" {{ $currentStatus == 'alpa' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 hover:bg-slate-50 transition shadow-2xs">
                                        Alpa
                                    </div>
                                </label>
                            </div>

                            <!-- Catatan -->
                            <div class="w-full sm:w-56">
                                <input type="text" name="absensi[{{ $siswa->id }}][catatan]" value="{{ $currentCatatan }}"
                                    placeholder="Keterangan opsional"
                                    class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">
                            Tidak ada siswa aktif di kelas ini.
                        </div>
                    @endforelse
                </div>

                @if($siswaList->isNotEmpty())
                    <div class="p-4 bg-slate-50/70 border-t border-slate-100/80 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-medium">Periksa kembali data sebelum menyimpan.</span>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                            Simpan Seluruh Absensi Kelas Ini
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
