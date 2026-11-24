@extends('layouts.app')

@section('title', 'Rekap Semester Guru')
@section('page_title', 'Rekapitulasi Kehadiran Semester')

@section('content')
<div class="space-y-6">

    @if(!$selectedKelas)
        <div class="custom-card p-8 text-center text-slate-500">
            <p>Anda belum ditugaskan mengelola rombel kelas.</p>
        </div>
    @else

        <!-- Header / Title -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekap Presensi Semester</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Laporan rekapitulasi kehadiran per semester siswa <strong>Kelas {{ $selectedKelas->nama_kelas }}</strong>.</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Tab Navigation -->
                <a href="{{ route('guru.rekap.bulanan', ['kelas_id' => $selectedKelas->id]) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 transition">
                    Rekap Bulanan
                </a>
                <a href="{{ route('guru.rekap.semester', ['kelas_id' => $selectedKelas->id]) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-xs">
                    Rekap Semester
                </a>
            </div>
        </div>

        <!-- Filter & Export Bar -->
        <div class="custom-card p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" action="{{ route('guru.rekap.semester') }}" class="flex flex-wrap items-center gap-3">
                    @if($kelasList->count() > 1)
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Kelas</label>
                            <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
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
                            <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Kelas</span>
                            <span class="inline-block px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-100 font-extrabold text-xs">
                                Kelas {{ $selectedKelas->nama_kelas }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Semester</label>
                        <select name="semester" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                            <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Juli - Des)</option>
                            <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Semester Genap (Jan - Jun)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight mb-1">Tahun Ajaran</label>
                        <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                                <option value="{{ $y }}" {{ $tahunMulai == $y ? 'selected' : '' }}>
                                    {{ $y }}/{{ $y + 1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Export Action Buttons -->
                <div class="flex items-center space-x-2 self-start sm:self-end">
                    <a href="{{ route('guru.rekap.semester.excel', ['kelas_id' => $selectedKelas->id, 'semester' => $semester, 'tahun' => $tahunMulai]) }}"
                        class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download Excel</span>
                    </a>

                    <a href="{{ route('guru.rekap.semester.pdf', ['kelas_id' => $selectedKelas->id, 'semester' => $semester, 'tahun' => $tahunMulai]) }}"
                        class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-600/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Download PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Badges Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-emerald-500">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">H</div>
                <div>
                    <span class="block text-[11px] font-bold text-emerald-800 uppercase tracking-tight">Hadir Semester</span>
                    <p class="text-2xl font-extrabold text-emerald-950 leading-tight mt-0.5">{{ $totalSemesterH }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-blue-500">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">I</div>
                <div>
                    <span class="block text-[11px] font-bold text-blue-800 uppercase tracking-tight">Izin Semester</span>
                    <p class="text-2xl font-extrabold text-blue-950 leading-tight mt-0.5">{{ $totalSemesterI }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-amber-500">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">S</div>
                <div>
                    <span class="block text-[11px] font-bold text-amber-800 uppercase tracking-tight">Sakit Semester</span>
                    <p class="text-2xl font-extrabold text-amber-950 leading-tight mt-0.5">{{ $totalSemesterS }}</p>
                </div>
            </div>
            <div class="oripio-card p-4 flex items-center space-x-3.5 border-l-4 border-l-rose-500">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-2xs">A</div>
                <div>
                    <span class="block text-[11px] font-bold text-rose-800 uppercase tracking-tight">Alpa Semester</span>
                    <p class="text-2xl font-extrabold text-rose-950 leading-tight mt-0.5">{{ $totalSemesterA }}</p>
                </div>
            </div>
        </div>

        <!-- Matriks Semester -->
        <div class="oripio-card overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <span class="font-bold text-slate-800">
                    Kelas {{ $selectedKelas->nama_kelas }} &bull; {{ $labelSemester }} &bull; TP {{ $tahunAjaran }}
                </span>
                <div class="flex items-center space-x-3 font-bold text-[11px]">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span> H: Hadir
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 border border-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span> I: Izin
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span> S: Sakit
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-600"></span> A: Alpa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold text-[11px]">
                            <th class="py-3 px-3 text-center border-r border-slate-200 w-10" rowspan="2">No</th>
                            <th class="py-3 px-4 border-r border-slate-200 w-24" rowspan="2">NIS</th>
                            <th class="py-3 px-4 border-r border-slate-200 min-w-[180px]" rowspan="2">Nama Siswa</th>
                            <th class="py-3 px-2 text-center border-r border-slate-200 w-10" rowspan="2">L/P</th>
                            @foreach($bulanList as $b)
                                <th class="py-2 px-2 text-center border-r border-slate-200 bg-emerald-50/50 text-emerald-900" colspan="4">
                                    {{ $b['nama'] }}
                                </th>
                            @endforeach
                            <th class="py-2 px-3 text-center bg-slate-100 border-r border-slate-200 text-slate-900" colspan="4">
                                TOTAL SEMESTER
                            </th>
                            <th class="py-3 px-3 text-center w-14 bg-slate-100" rowspan="2">%</th>
                        </tr>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-500 font-bold text-[10px]">
                            @foreach($bulanList as $b)
                                <th class="py-1 px-1.5 text-center text-emerald-700">H</th>
                                <th class="py-1 px-1.5 text-center text-blue-700">I</th>
                                <th class="py-1 px-1.5 text-center text-amber-700">S</th>
                                <th class="py-1 px-1.5 text-center text-rose-700 border-r border-slate-200">A</th>
                            @endforeach
                            <th class="py-1 px-2 text-center text-emerald-800 bg-slate-100 font-extrabold">H</th>
                            <th class="py-1 px-2 text-center text-blue-800 bg-slate-100 font-extrabold">I</th>
                            <th class="py-1 px-2 text-center text-amber-800 bg-slate-100 font-extrabold">S</th>
                            <th class="py-1 px-2 text-center text-rose-800 bg-slate-100 font-extrabold border-r border-slate-200">A</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($matrix as $index => $item)
                            @php $s = $item['siswa']; @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-2.5 px-3 text-center font-bold text-slate-500 border-r border-slate-100">
                                    {{ $s->no_absen ?? ($index + 1) }}
                                </td>
                                <td class="py-2.5 px-4 font-mono text-slate-600 border-r border-slate-100">
                                    {{ $s->nis }}
                                </td>
                                <td class="py-2.5 px-4 font-bold text-slate-900 border-r border-slate-100 whitespace-nowrap">
                                    {{ $s->nama_lengkap }}
                                </td>
                                <td class="py-2.5 px-2 text-center font-bold text-slate-600 border-r border-slate-100">
                                    {{ $s->jenis_kelamin }}
                                </td>

                                @foreach($item['bulanBreakdown'] as $mb)
                                    <td class="py-2 px-1 text-center font-medium text-emerald-700">{{ $mb['hadir'] }}</td>
                                    <td class="py-2 px-1 text-center font-medium text-blue-700">{{ $mb['izin'] }}</td>
                                    <td class="py-2 px-1 text-center font-medium text-amber-700">{{ $mb['sakit'] }}</td>
                                    <td class="py-2 px-1 text-center font-medium text-rose-700 border-r border-slate-100">{{ $mb['alpa'] }}</td>
                                @endforeach

                                <td class="py-2.5 px-2 text-center font-extrabold text-emerald-700 bg-slate-50/50">{{ $item['totalH'] }}</td>
                                <td class="py-2.5 px-2 text-center font-extrabold text-blue-700 bg-slate-50/50">{{ $item['totalI'] }}</td>
                                <td class="py-2.5 px-2 text-center font-extrabold text-amber-700 bg-slate-50/50">{{ $item['totalS'] }}</td>
                                <td class="py-2.5 px-2 text-center font-extrabold text-rose-700 bg-slate-50/50 border-r border-slate-100">{{ $item['totalA'] }}</td>
                                <td class="py-2.5 px-3 text-center font-extrabold text-slate-900 bg-slate-50/50">{{ $item['persentase'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + (count($bulanList) * 4) + 5 }}" class="py-12 text-center text-slate-400">
                                    Belum ada data siswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>
@endsection
