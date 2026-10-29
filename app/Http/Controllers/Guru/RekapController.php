<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    /**
     * Rekap Bulanan untuk Guru (hanya kelas yang diajar/wali)
     */
    public function bulanan(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasList = Kelas::whereIn('id', $accessibleKelasIds)->get();

        if ($kelasList->isEmpty()) {
            return view('guru.rekap.bulanan', [
                'kelasList' => collect(),
                'selectedKelas' => null,
                'bulan' => Carbon::now()->month,
                'tahun' => Carbon::now()->year,
                'monthDates' => [],
                'matrix' => [],
                'totalHadirBulan' => 0,
                'totalIzinBulan' => 0,
                'totalSakitBulan' => 0,
                'totalAlpaBulan' => 0,
            ])->with('error', 'Anda belum memiliki kelas yang diampu.');
        }

        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelasId = $request->query('kelas_id', $kelasList->first()->id);

        // Security check: Guru only accesses their classes
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat rekap kelas ini.');
        }

        $selectedKelas = $kelasList->firstWhere('id', $kelasId);

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

        // Print mode
        if ($request->query('print') == '1') {
            return view('guru.rekap.bulanan-print', compact(
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

        return view('guru.rekap.bulanan', compact(
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
