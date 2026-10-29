<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    /**
     * Rekap Harian untuk TU (bisa akses semua kelas)
     */
    public function harian(Request $request)
    {
        $tanggal = $request->query('tanggal', Carbon::today()->format('Y-m-d'));
        $kelasId = $request->query('kelas_id');

        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();

        $query = Absensi::with(['siswa', 'kelas', 'guru'])
            ->where('absensi.tanggal', $tanggal);

        if ($kelasId) {
            $query->where('absensi.kelas_id', $kelasId);
        }

        $absensiList = $query->join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
            ->orderBy('siswa.nama_lengkap', 'asc')
            ->select('absensi.*')
            ->get();

        // Rekap ringkasan
        $totalHadir = $absensiList->where('status', 'hadir')->count();
        $totalIzin = $absensiList->where('status', 'izin')->count();
        $totalSakit = $absensiList->where('status', 'sakit')->count();
        $totalAlpa = $absensiList->where('status', 'alpa')->count();
        $totalSiswaTercatat = $absensiList->count();

        $selectedKelas = $kelasId ? Kelas::find($kelasId) : null;

        // Print view
        if ($request->query('print') == '1') {
            return view('tu.rekap.harian-print', compact(
                'tanggal',
                'kelasId',
                'selectedKelas',
                'absensiList',
                'totalHadir',
                'totalIzin',
                'totalSakit',
                'totalAlpa',
                'totalSiswaTercatat'
            ));
        }

        return view('tu.rekap.harian', compact(
            'tanggal',
            'kelasId',
            'kelasList',
            'selectedKelas',
            'absensiList',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'totalSiswaTercatat'
        ));
    }

    /**
     * Rekap Bulanan untuk TU (bisa akses semua kelas)
     */
    public function bulanan(Request $request)
    {
        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $kelasId = $request->query('kelas_id', $kelasList->first()?->id);

        $selectedKelas = $kelasId ? Kelas::with('waliKelas')->find($kelasId) : null;

        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $monthDates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $monthDates[] = Carbon::createFromDate($tahun, $bulan, $day);
        }

        $matrix = [];
        $totalHadirBulan = 0;
        $totalIzinBulan = 0;
        $totalSakitBulan = 0;
        $totalAlpaBulan = 0;

        if ($selectedKelas) {
            $siswaList = Siswa::where('kelas_id', $selectedKelas->id)
                ->where('status', 'aktif')
                ->orderBy('no_absen', 'asc')
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

            $absensiRecords = Absensi::where('kelas_id', $selectedKelas->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->groupBy('siswa_id');

            foreach ($siswaList as $siswa) {
                $siswaAbsensi = $absensiRecords->get($siswa->id, collect());
                $h = 0;
                $i = 0;
                $s = 0;
                $a = 0;

                $dailyStatus = [];
                foreach ($monthDates as $cDate) {
                    $dStr = $cDate->format('Y-m-d');
                    $record = $siswaAbsensi->firstWhere('tanggal', $dStr);
                    $status = $record ? $record->status : null;
                    $dailyStatus[$cDate->day] = [
                        'status' => $status,
                        'is_weekend' => $cDate->isWeekend(),
                    ];

                    if ($status === 'hadir') $h++;
                    elseif ($status === 'izin') $i++;
                    elseif ($status === 'sakit') $s++;
                    elseif ($status === 'alpa') $a++;
                }

                $totalRecorded = $h + $i + $s + $a;
                $persentase = $totalRecorded > 0 ? round(($h / $totalRecorded) * 100, 1) : 0;

                $totalHadirBulan += $h;
                $totalIzinBulan += $i;
                $totalSakitBulan += $s;
                $totalAlpaBulan += $a;

                $matrix[] = [
                    'siswa' => $siswa,
                    'daily' => $dailyStatus,
                    'h' => $h,
                    'i' => $i,
                    's' => $s,
                    'a' => $a,
                    'total' => $totalRecorded,
                    'persen' => $persentase,
                ];
            }
        }

        if ($request->query('print') == '1') {
            return view('tu.rekap.bulanan-print', compact(
                'bulan',
                'tahun',
                'selectedKelas',
                'monthDates',
                'matrix',
                'totalHadirBulan',
                'totalIzinBulan',
                'totalSakitBulan',
                'totalAlpaBulan'
            ));
        }

        return view('tu.rekap.bulanan', compact(
            'bulan',
            'tahun',
            'kelasId',
            'kelasList',
            'selectedKelas',
            'monthDates',
            'matrix',
            'totalHadirBulan',
            'totalIzinBulan',
            'totalSakitBulan',
            'totalAlpaBulan'
        ));
    }
}

