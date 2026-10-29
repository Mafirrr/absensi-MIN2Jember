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

        $totalKelas = Kelas::count();
        $totalSiswa = Siswa::where('status', 'aktif')->count();
        $totalGuru = User::where('role', 'guru')->count();

        // Absensi hari ini
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

        // Status per kelas hari ini
        $kelasList = Kelas::with(['waliKelas', 'siswa' => function ($q) {
            $q->where('status', 'aktif');
        }])->get()->map(function ($kelas) use ($today) {
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

        // Tren 7 hari terakhir (hari kerja)
        $dates = [];
        $trendHadir = [];
        $trendIzin = [];
        $trendSakit = [];
        $trendAlpa = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dStr = $d->format('Y-m-d');
            $dates[] = $d->translatedFormat('d M');

            $rec = Absensi::where('tanggal', $dStr)->get();
            $trendHadir[] = $rec->where('status', 'hadir')->count();
            $trendIzin[] = $rec->where('status', 'izin')->count();
            $trendSakit[] = $rec->where('status', 'sakit')->count();
            $trendAlpa[] = $rec->where('status', 'alpa')->count();
        }

        return view('tu.dashboard', compact(
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
            'dates',
            'trendHadir',
            'trendIzin',
            'trendSakit',
            'trendAlpa'
        ));
    }
}
