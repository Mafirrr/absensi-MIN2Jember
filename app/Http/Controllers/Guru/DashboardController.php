<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasList = Kelas::whereIn('id', $accessibleKelasIds)->get();

        // Selected kelas
        $selectedKelasId = $request->query('kelas_id', $kelasList->first()?->id);
        $selectedKelas = $kelasList->firstWhere('id', $selectedKelasId);

        $today = Carbon::today()->format('Y-m-d');
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Default empty stats
        $totalSiswa = 0;
        $hadirHariIni = 0;
        $izinHariIni = 0;
        $sakitHariIni = 0;
        $alpaHariIni = 0;
        $sudahInputHariIni = false;

        $statistikPerSiswa = [];
        $totalHadirBulanIni = 0;
        $totalIzinBulanIni = 0;
        $totalSakitBulanIni = 0;
        $totalAlpaBulanIni = 0;

        if ($selectedKelas) {
            $siswaList = Siswa::where('kelas_id', $selectedKelas->id)
                ->where('status', 'aktif')
                ->orderBy('no_absen', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $totalSiswa = $siswaList->count();

            // Status absensi hari ini di kelas ini
            $absensiHariIni = Absensi::where('kelas_id', $selectedKelas->id)
                ->where('tanggal', $today)
                ->get();

            $sudahInputHariIni = $absensiHariIni->count() > 0;
            $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
            $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
            $sakitHariIni = $absensiHariIni->where('status', 'sakit')->count();
            $alpaHariIni = $absensiHariIni->where('status', 'alpa')->count();

            // Statistik kehadiran per status siswa (Bulan Ini)
            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

            $absensiBulanIni = Absensi::where('kelas_id', $selectedKelas->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->get()
                ->groupBy('siswa_id');

            foreach ($siswaList as $siswa) {
                $recs = $absensiBulanIni->get($siswa->id, collect());
                $h = $recs->where('status', 'hadir')->count();
                $i = $recs->where('status', 'izin')->count();
                $s = $recs->where('status', 'sakit')->count();
                $a = $recs->where('status', 'alpa')->count();
                $totalPertemuan = $h + $i + $s + $a;
                $persentase = $totalPertemuan > 0 ? round(($h / $totalPertemuan) * 100, 1) : 0;

                $totalHadirBulanIni += $h;
                $totalIzinBulanIni += $i;
                $totalSakitBulanIni += $s;
                $totalAlpaBulanIni += $a;

                $statistikPerSiswa[] = [
                    'id' => $siswa->id,
                    'no_absen' => $siswa->no_absen,
                    'nis' => $siswa->nis,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'hadir' => $h,
                    'izin' => $i,
                    'sakit' => $s,
                    'alpa' => $a,
                    'total' => $totalPertemuan,
                    'persentase' => $persentase,
                ];
            }
        }

        // Tren 7 hari terakhir untuk rombel kelas yang dipilih
        $trendDates = [];
        $trendHadir = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dStr = $d->format('Y-m-d');
            $trendDates[] = $d->translatedFormat('d M');

            if ($selectedKelas) {
                $count = Absensi::where('kelas_id', $selectedKelas->id)
                    ->where('tanggal', $dStr)
                    ->where('status', 'hadir')
                    ->count();
                $trendHadir[] = $count;
            } else {
                $trendHadir[] = 0;
            }
        }

        return view('guru.dashboard', compact(
            'kelasList',
            'selectedKelas',
            'totalSiswa',
            'hadirHariIni',
            'izinHariIni',
            'sakitHariIni',
            'alpaHariIni',
            'sudahInputHariIni',
            'statistikPerSiswa',
            'totalHadirBulanIni',
            'totalIzinBulanIni',
            'totalSakitBulanIni',
            'totalAlpaBulanIni',
            'trendDates',
            'trendHadir',
            'today',
            'currentMonth',
            'currentYear'
        ));
    }
}

