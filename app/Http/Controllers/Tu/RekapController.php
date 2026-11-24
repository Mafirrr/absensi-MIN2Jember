<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\RekapExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    protected RekapExportService $exportService;

    public function __construct(RekapExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Rekap Harian untuk TU (bisa akses semua kelas)
     */
    public function harian(Request $request)
    {
        $tanggal = $request->query('tanggal', Carbon::today()->format('Y-m-d'));
        $kelasId = $request->query('kelas_id');

        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();

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

        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $kelasId = $request->query('kelas_id', $kelasList->first()?->id);

        $selectedKelas = $kelasId ? Kelas::with('waliKelas')->find($kelasId) : null;

        $matrix = [];
        $totalHadirBulan = 0;
        $totalIzinBulan = 0;
        $totalSakitBulan = 0;
        $totalAlpaBulan = 0;
        $monthDates = [];

        if ($selectedKelas) {
            $data = $this->exportService->getMonthlyData($selectedKelas, $bulan, $tahun);
            $monthDates = $data['monthDates'];
            $matrix = $data['matrix'];
            $totalHadirBulan = $data['totalH'];
            $totalIzinBulan = $data['totalI'];
            $totalSakitBulan = $data['totalS'];
            $totalAlpaBulan = $data['totalA'];
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

    /**
     * Download Excel Rekap Bulanan TU
     */
    public function exportBulananExcel(Request $request)
    {
        $kelasId = (int) $request->query('kelas_id');
        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getMonthlyData($kelas, $bulan, $tahun);

        return $this->exportService->exportMonthlyExcel($data);
    }

    /**
     * Download PDF Rekap Bulanan TU
     */
    public function exportBulananPdf(Request $request)
    {
        $kelasId = (int) $request->query('kelas_id');
        $bulan = (int) $request->query('bulan', Carbon::now()->month);
        $tahun = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getMonthlyData($kelas, $bulan, $tahun);

        return $this->exportService->exportMonthlyPdf($data);
    }

    /**
     * Rekap Semesteran untuk TU
     */
    public function semester(Request $request)
    {
        $semester = $request->query('semester', Carbon::now()->month >= 7 ? 'ganjil' : 'genap');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->month >= 7 ? Carbon::now()->year : Carbon::now()->year - 1);

        $kelasList = Kelas::orderBy('tingkat', 'asc')->orderBy('nama_kelas', 'asc')->get();
        $kelasId = $request->query('kelas_id', $kelasList->first()?->id);

        $selectedKelas = $kelasId ? Kelas::with('waliKelas')->find($kelasId) : null;

        $matrix = [];
        $totalSemesterH = 0;
        $totalSemesterI = 0;
        $totalSemesterS = 0;
        $totalSemesterA = 0;
        $bulanList = [];
        $labelSemester = '';
        $tahunAjaran = '';

        if ($selectedKelas) {
            $data = $this->exportService->getSemesterData($selectedKelas, $semester, $tahunMulai);
            $matrix = $data['matrix'];
            $totalSemesterH = $data['totalH'];
            $totalSemesterI = $data['totalI'];
            $totalSemesterS = $data['totalS'];
            $totalSemesterA = $data['totalA'];
            $bulanList = $data['bulanList'];
            $labelSemester = $data['labelSemester'];
            $tahunAjaran = $data['tahunAjaran'];
        }

        return view('tu.rekap.semester', compact(
            'semester',
            'tahunMulai',
            'tahunAjaran',
            'labelSemester',
            'kelasId',
            'kelasList',
            'selectedKelas',
            'bulanList',
            'matrix',
            'totalSemesterH',
            'totalSemesterI',
            'totalSemesterS',
            'totalSemesterA'
        ));
    }

    /**
     * Download Excel Rekap Semester TU
     */
    public function exportSemesterExcel(Request $request)
    {
        $kelasId = (int) $request->query('kelas_id');
        $semester = $request->query('semester', 'ganjil');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getSemesterData($kelas, $semester, $tahunMulai);

        return $this->exportService->exportSemesterExcel($data);
    }

    /**
     * Download PDF Rekap Semester TU
     */
    public function exportSemesterPdf(Request $request)
    {
        $kelasId = (int) $request->query('kelas_id');
        $semester = $request->query('semester', 'ganjil');
        $tahunMulai = (int) $request->query('tahun', Carbon::now()->year);

        $kelas = Kelas::with('waliKelas')->findOrFail($kelasId);
        $data = $this->exportService->getSemesterData($kelas, $semester, $tahunMulai);

        return $this->exportService->exportSemesterPdf($data);
    }
}
