<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $allKelas = Kelas::with(['waliKelas', 'siswa' => function ($q) {
            $q->where('status', 'aktif');
        }])->orderBy('nama_kelas')->get();

        $kelasId = $request->query('kelas_id');
        $selectedKelas = $kelasId ? $allKelas->firstWhere('id', $kelasId) : null;

        $totalKelas = $allKelas->count();
        $totalGuru = User::where('role', 'guru')->count();

        // Status per kelas hari ini (selalu dihitung untuk data seluruh kelas)
        $kelasList = $allKelas->map(function ($kelas) use ($today) {
            $absensi = Absensi::where('kelas_id', $kelas->id)
                ->where('tanggal', $today)
                ->get();

            $totalSiswaKelas = $kelas->siswa->count();
            $sudahInput = $absensi->count() > 0;
            $hadir = $absensi->where('status', 'hadir')->count();
            $izin = $absensi->where('status', 'izin')->count();
            $sakit = $absensi->where('status', 'sakit')->count();
            $alpa = $absensi->where('status', 'alpa')->count();

            return [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'jurusan' => $kelas->jurusan,
                'wali_kelas' => $kelas->waliKelas ? $kelas->waliKelas->name : 'Belum ditentukan',
                'total_siswa' => $totalSiswaKelas,
                'sudah_input' => $sudahInput,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persen' => $totalSiswaKelas > 0 && $sudahInput ? round(($hadir / $totalSiswaKelas) * 100) : 0,
            ];
        });

        $kelasSudahInput = $kelasList->where('sudah_input', true)->count();
        $kelasBelumInput = $kelasList->where('sudah_input', false)->count();

        // Hitung statistik (Semua kelas vs Kelas Terpilih)
        if ($selectedKelas) {
            $totalSiswa = Siswa::where('kelas_id', $selectedKelas->id)->where('status', 'aktif')->count();
            $absensiHariIni = Absensi::where('kelas_id', $selectedKelas->id)->where('tanggal', $today)->get();
            $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
            $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
            $sakitHariIni = $absensiHariIni->where('status', 'sakit')->count();
            $alpaHariIni = $absensiHariIni->where('status', 'alpa')->count();
            $totalInputHariIni = $absensiHariIni->count();
            $belumDiabsen = max(0, $totalSiswa - $totalInputHariIni);
            $persenKehadiran = $totalInputHariIni > 0 
                ? round(($hadirHariIni / $totalInputHariIni) * 100, 1) 
                : 0;

            // Daftar siswa di kelas terpilih dengan status absensi hari ini
            $siswaList = Siswa::where('kelas_id', $selectedKelas->id)
                ->where('status', 'aktif')
                ->orderBy('no_absen', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->get()
                ->map(function ($s) use ($absensiHariIni) {
                    $rec = $absensiHariIni->firstWhere('siswa_id', $s->id);
                    return [
                        'id' => $s->id,
                        'no_absen' => $s->no_absen,
                        'nis' => $s->nis,
                        'nama_lengkap' => $s->nama_lengkap,
                        'jenis_kelamin' => $s->jenis_kelamin,
                        'status' => $rec ? $rec->status : null,
                        'keterangan' => $rec ? $rec->keterangan : null,
                    ];
                });
        } else {
            $totalSiswa = Siswa::where('status', 'aktif')->count();
            $absensiHariIni = Absensi::where('tanggal', $today)->get();
            $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
            $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
            $sakitHariIni = $absensiHariIni->where('status', 'sakit')->count();
            $alpaHariIni = $absensiHariIni->where('status', 'alpa')->count();
            $totalInputHariIni = $absensiHariIni->count();
            $belumDiabsen = max(0, $totalSiswa - $totalInputHariIni);
            $persenKehadiran = $totalInputHariIni > 0 
                ? round(($hadirHariIni / $totalInputHariIni) * 100, 1) 
                : 0;
            $siswaList = collect();
        }

        // Filter periode tren kehadiran (menghormati selectedKelas jika ada)
        $periode = $request->query('periode', $request->query('filter', '7'));
        $dates = [];
        $trendHadir = [];
        $trendIzin = [];
        $trendSakit = [];
        $trendAlpa = [];

        if ($periode === '14') {
            $periodeLabel = '14 Hari';
            $daysCount = 14;
        } elseif ($periode === '30') {
            $periodeLabel = '30 Hari';
            $daysCount = 30;
        } elseif ($periode === 'bulan_ini') {
            $periodeLabel = 'Bulan Ini';
            $daysCount = Carbon::today()->day;
        } else {
            $periode = '7';
            $periodeLabel = '7 Hari';
            $daysCount = 7;
        }

        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dStr = $d->format('Y-m-d');
            $dates[] = $d->translatedFormat('d M');

            $q = Absensi::where('tanggal', $dStr);
            if ($selectedKelas) {
                $q->where('kelas_id', $selectedKelas->id);
            }
            $rec = $q->get();
            $trendHadir[] = $rec->where('status', 'hadir')->count();
            $trendIzin[] = $rec->where('status', 'izin')->count();
            $trendSakit[] = $rec->where('status', 'sakit')->count();
            $trendAlpa[] = $rec->where('status', 'alpa')->count();
        }

        return view('tu.dashboard', compact(
            'allKelas',
            'kelasId',
            'selectedKelas',
            'totalKelas',
            'totalSiswa',
            'totalGuru',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alpaHariIni',
            'belumDiabsen',
            'persenKehadiran',
            'kelasList',
            'kelasSudahInput',
            'kelasBelumInput',
            'siswaList',
            'periode',
            'periodeLabel',
            'dates',
            'trendHadir',
            'trendIzin',
            'trendSakit',
            'trendAlpa'
        ));
    }
}
