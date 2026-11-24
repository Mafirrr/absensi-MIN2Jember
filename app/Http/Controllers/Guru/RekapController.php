<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Services\RekapExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    protected RekapExportService $exportService;

    public function __construct(RekapExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Rekap Bulanan untuk Guru
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

        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat rekap kelas ini.');
        }

        $selectedKelas = $kelasList->firstWhere('id', $kelasId);
        $data = $this->exportService->getMonthlyData($selectedKelas, $bulan, $tahun);

        return view('guru.rekap.bulanan', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'kelasId' => $kelasId,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'monthDates' => $data['monthDates'],
            'matrix' => $data['matrix'],
            'totalHadirBulan' => $data['totalH'],
            'totalIzinBulan' => $data['totalI'],
            'totalSakitBulan' => $data['totalS'],
            'totalAlpaBulan' => $data['totalA'],
        ]);
    }

    public function exportBulananExcel(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasId = (int) $request->query('kelas_id');
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Akses ditolak.');
        }

        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getMonthlyData($kelas, $bulan, $tahun);

        return $this->exportService->exportMonthlyExcel($data);
    }

    public function exportBulananPdf(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasId = (int) $request->query('kelas_id');
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Akses ditolak.');
        }

        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getMonthlyData($kelas, $bulan, $tahun);

        return $this->exportService->exportMonthlyPdf($data);
    }

    /**
     * Rekap Semesteran untuk Guru
     */
    public function semester(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasList = Kelas::whereIn('id', $accessibleKelasIds)->get();

        if ($kelasList->isEmpty()) {
            return view('guru.rekap.semester', [
                'kelasList' => collect(),
                'selectedKelas' => null,
                'semester' => 'ganjil',
                'tahunMulai' => Carbon::now()->year,
                'tahunAjaran' => '',
                'labelSemester' => '',
                'bulanList' => [],
                'matrix' => [],
                'totalSemesterH' => 0,
                'totalSemesterI' => 0,
                'totalSemesterS' => 0,
                'totalSemesterA' => 0,
            ])->with('error', 'Anda belum memiliki kelas yang diampu.');
        }

        $semester = $request->query('semester', Carbon::now()->month >= 7 ? 'ganjil' : 'genap');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->month >= 7 ? Carbon::now()->year : Carbon::now()->year - 1);
        $kelasId = $request->query('kelas_id', $kelasList->first()->id);

        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat rekap kelas ini.');
        }

        $selectedKelas = $kelasList->firstWhere('id', $kelasId);
        $data = $this->exportService->getSemesterData($selectedKelas, $semester, $tahunMulai);

        return view('guru.rekap.semester', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'kelasId' => $kelasId,
            'semester' => $semester,
            'tahunMulai' => $tahunMulai,
            'tahunAjaran' => $data['tahunAjaran'],
            'labelSemester' => $data['labelSemester'],
            'bulanList' => $data['bulanList'],
            'matrix' => $data['matrix'],
            'totalSemesterH' => $data['totalH'],
            'totalSemesterI' => $data['totalI'],
            'totalSemesterS' => $data['totalS'],
            'totalSemesterA' => $data['totalA'],
        ]);
    }

    public function exportSemesterExcel(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasId = (int) $request->query('kelas_id');
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Akses ditolak.');
        }

        $semester = $request->query('semester', 'ganjil');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getSemesterData($kelas, $semester, $tahunMulai);

        return $this->exportService->exportSemesterExcel($data);
    }

    public function exportSemesterPdf(Request $request)
    {
        $user = Auth::user();
        $accessibleKelasIds = $user->getAccessibleKelasIds();

        $kelasId = (int) $request->query('kelas_id');
        if (!in_array($kelasId, $accessibleKelasIds)) {
            abort(403, 'Akses ditolak.');
        }

        $semester = $request->query('semester', 'ganjil');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getSemesterData($kelas, $semester, $tahunMulai);

        return $this->exportService->exportSemesterPdf($data);
    }
}
