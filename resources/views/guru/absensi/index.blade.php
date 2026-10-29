@extends('layouts.app')

@section('title', 'Input Absensi Siswa')
@section('page_title', 'Input Presensi Siswa')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-2xl text-amber-800 text-sm">
            <p class="font-bold">Akses Terbatas:</p>
            <p>Anda belum memiliki kelas yang ditugaskan untuk diinput absensinya. Silakan hubungi bagian Tata Usaha (TU).</p>
        </div>
    @else

        <!-- Filter & Control Header Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Select Form for Class and Date -->
                <form method="GET" action="{{ route('guru.absensi.index') }}" class="flex flex-wrap items-center gap-3">
                    <!-- Kelas -->
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kelas yang Diajar</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
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
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kelas yang Dikelola</span>
                            <span class="inline-block px-3 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Absensi</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="pt-5">
                        <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                            Muat Data
                        </button>
                    </div>
                </form>

                <!-- Quick Action Buttons -->
                <div class="flex items-center space-x-2 pt-2 lg:pt-0">
                    <button type="button" onclick="markAll('hadir')"
                        class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Tandai Semua Hadir</span>
                    </button>

                    <button type="submit" form="formAbsensi"
                        class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span>Simpan Absensi</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Recording Status Banner -->
        @if($existingAbsensi->isNotEmpty())
            <div class="flex items-center justify-between p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-medium">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Absensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong> sudah pernah diinput. Anda dapat mengubah status kehadiran dan menekan Simpan.</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-blue-200 text-blue-900 font-bold text-[10px]">Mode Edit</span>
            </div>
        @endif

        <!-- Main Form -->
        <form method="POST" action="{{ route('guru.absensi.store') }}" id="formAbsensi">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50/75 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Daftar Siswa Kelas {{ $selectedKelas->nama_kelas }} ({{ $siswaList->count() }} Siswa)
                    </h3>
                    <span class="text-xs text-slate-500">Pilih status untuk setiap siswa</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($siswaList as $idx => $siswa)
                        @php
                            $currentStatus = old("absensi.{$siswa->id}.status", $existingAbsensi[$siswa->id]->status ?? 'hadir');
                            $currentCatatan = old("absensi.{$siswa->id}.catatan", $existingAbsensi[$siswa->id]->catatan ?? '');
                        @endphp
                        <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/60 transition">
                            <!-- Student Info -->
                            <div class="flex items-center space-x-3.5 min-w-[220px]">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ $siswa->no_absen ?? ($idx + 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $siswa->nama_lengkap }}</h4>
                                    <div class="flex items-center space-x-2 text-[11px] text-slate-500 mt-0.5">
                                        <span class="font-mono">NIS: {{ $siswa->nis }}</span>
                                        <span>&bull;</span>
                                        <span class="font-semibold {{ $siswa->jenis_kelamin == 'L' ? 'text-sky-600' : 'text-pink-600' }}">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Radio Buttons -->
                            <div class="flex items-center space-x-2">
                                <!-- Hadir -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="hadir"
                                        class="peer sr-only status-hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 hover:bg-slate-50 transition shadow-2xs">
                                        Hadir
                                    </div>
                                </label>

                                <!-- Izin -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="izin"
                                        class="peer sr-only status-izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-50 transition shadow-2xs">
                                        Izin
                                    </div>
                                </label>

                                <!-- Sakit -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="sakit"
                                        class="peer sr-only status-sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 hover:bg-slate-50 transition shadow-2xs">
                                        Sakit
                                    </div>
                                </label>

                                <!-- Alpa -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="alpa"
                                        class="peer sr-only status-alpa" {{ $currentStatus == 'alpa' ? 'checked' : '' }}>
                                    <div class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 hover:bg-slate-50 transition shadow-2xs">
                                        Alpa
                                    </div>
                                </label>
                            </div>

                            <!-- Catatan / Keterangan -->
                            <div class="w-full md:w-56">
                                <input type="text" name="absensi[{{ $siswa->id }}][catatan]" value="{{ $currentCatatan }}"
                                    placeholder="Catatan (alasan izin/sakit)"
                                    class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">
                            Tidak ada data siswa aktif di kelas ini.
                        </div>
                    @endforelse
                </div>

                @if($siswaList->isNotEmpty())
                    <div class="p-5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">Periksa kembali sebelum menyimpan data presensi.</span>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
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
        const radios = document.querySelectorAll(`.status-${status}`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endpush
