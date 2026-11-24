<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapExportService
{
    /**
     * Ambil data matriks absensi bulanan
     */
    public function getMonthlyData(Kelas $kelas, int $bulan, int $tahun): array
    {
        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $monthDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $monthDates[] = Carbon::createFromDate($tahun, $bulan, $d);
        }

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

        $siswaList = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('no_absen', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $absensiRecords = Absensi::where('kelas_id', $kelas->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy('siswa_id');

        $matrix = [];
        $totalH = 0;
        $totalI = 0;
        $totalS = 0;
        $totalA = 0;

        foreach ($siswaList as $siswa) {
            $siswaAbsensi = $absensiRecords->get($siswa->id, collect())
                ->keyBy(fn ($a) => is_string($a->tanggal) ? substr($a->tanggal, 0, 10) : $a->tanggal->format('Y-m-d'));

            $h = 0;
            $i = 0;
            $s = 0;
            $a = 0;
            $daily = [];

            foreach ($monthDates as $cDate) {
                $dStr = $cDate->format('Y-m-d');
                $record = $siswaAbsensi->get($dStr);
                $status = $record ? $record->status : null;
                $daily[$cDate->day] = [
                    'status' => $status,
                    'is_weekend' => $cDate->isWeekend(),
                ];

                if ($status === 'hadir') $h++;
                elseif ($status === 'izin') $i++;
                elseif ($status === 'sakit') $s++;
                elseif ($status === 'alpa') $a++;
            }

            $totalMasuk = $h + $i + $s + $a;
            $persen = $totalMasuk > 0 ? round(($h / $totalMasuk) * 100, 1) : 0;

            $matrix[] = [
                'siswa' => $siswa,
                'daily' => $daily,
                'hadir' => $h,
                'izin' => $i,
                'sakit' => $s,
                'alpa' => $a,
                'h' => $h,
                'i' => $i,
                's' => $s,
                'a' => $a,
                'total' => $totalMasuk,
                'persentase' => $persen,
                'persen' => $persen,
            ];

            $totalH += $h;
            $totalI += $i;
            $totalS += $s;
            $totalA += $a;
        }

        return [
            'kelas' => $kelas,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'monthDates' => $monthDates,
            'matrix' => $matrix,
            'totalH' => $totalH,
            'totalI' => $totalI,
            'totalS' => $totalS,
            'totalA' => $totalA,
        ];
    }

    /**
     * Ambil data rekapitulasi semester
     * Ganjil: Juli (7) - Desember (12)
     * Genap: Januari (1) - Juni (6)
     */
    public function getSemesterData(Kelas $kelas, string $semester, int $tahunMulai): array
    {
        $bulanList = [];
        if (strtolower($semester) === 'genap') {
            $tahunAktif = $tahunMulai + 1;
            for ($m = 1; $m <= 6; $m++) {
                $bulanList[] = ['bulan' => $m, 'tahun' => $tahunAktif, 'nama' => Carbon::create($tahunAktif, $m, 1)->translatedFormat('F')];
            }
            $startDate = Carbon::create($tahunAktif, 1, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::create($tahunAktif, 6, 1)->endOfMonth()->format('Y-m-d');
            $labelSemester = 'SEMESTER GENAP (Januari - Juni ' . $tahunAktif . ')';
        } else {
            // Ganjil
            for ($m = 7; $m <= 12; $m++) {
                $bulanList[] = ['bulan' => $m, 'tahun' => $tahunMulai, 'nama' => Carbon::create($tahunMulai, $m, 1)->translatedFormat('F')];
            }
            $startDate = Carbon::create($tahunMulai, 7, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::create($tahunMulai, 12, 1)->endOfMonth()->format('Y-m-d');
            $labelSemester = 'SEMESTER GANJIL (Juli - Desember ' . $tahunMulai . ')';
        }

        $siswaList = Siswa::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->orderBy('no_absen', 'asc')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        $absensiRecords = Absensi::where('kelas_id', $kelas->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy('siswa_id');

        $matrix = [];
        $totalSemesterH = 0;
        $totalSemesterI = 0;
        $totalSemesterS = 0;
        $totalSemesterA = 0;

        foreach ($siswaList as $siswa) {
            $records = $absensiRecords->get($siswa->id, collect());
            $bulanBreakdown = [];
            $totalH = 0;
            $totalI = 0;
            $totalS = 0;
            $totalA = 0;

            foreach ($bulanList as $b) {
                $mRecords = $records->filter(function ($a) use ($b) {
                    $dt = is_string($a->tanggal) ? Carbon::parse($a->tanggal) : $a->tanggal;
                    return $dt->month == $b['bulan'] && $dt->year == $b['tahun'];
                });

                $h = $mRecords->where('status', 'hadir')->count();
                $i = $mRecords->where('status', 'izin')->count();
                $s = $mRecords->where('status', 'sakit')->count();
                $a = $mRecords->where('status', 'alpa')->count();

                $bulanBreakdown[] = [
                    'bulan' => $b['nama'],
                    'hadir' => $h,
                    'izin' => $i,
                    'sakit' => $s,
                    'alpa' => $a,
                ];

                $totalH += $h;
                $totalI += $i;
                $totalS += $s;
                $totalA += $a;
            }

            $totalPresensi = $totalH + $totalI + $totalS + $totalA;
            $persen = $totalPresensi > 0 ? round(($totalH / $totalPresensi) * 100, 1) : 0;

            $matrix[] = [
                'siswa' => $siswa,
                'bulanBreakdown' => $bulanBreakdown,
                'totalH' => $totalH,
                'totalI' => $totalI,
                'totalS' => $totalS,
                'totalA' => $totalA,
                'persentase' => $persen,
            ];

            $totalSemesterH += $totalH;
            $totalSemesterI += $totalI;
            $totalSemesterS += $totalS;
            $totalSemesterA += $totalA;
        }

        return [
            'kelas' => $kelas,
            'semester' => $semester,
            'tahunMulai' => $tahunMulai,
            'tahunAjaran' => $tahunMulai . '/' . ($tahunMulai + 1),
            'labelSemester' => $labelSemester,
            'bulanList' => $bulanList,
            'matrix' => $matrix,
            'totalH' => $totalSemesterH,
            'totalI' => $totalSemesterI,
            'totalS' => $totalSemesterS,
            'totalA' => $totalSemesterA,
        ];
    }

    /**
     * Export Rekap Bulanan ke Dokumen Excel (.xlsx)
     */
    public function exportMonthlyExcel(array $data): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap ' . Carbon::create(null, $data['bulan'])->translatedFormat('M Y'));

        $kelas = $data['kelas'];
        $monthDates = $data['monthDates'];
        $numDays = count($monthDates);

        // Header Dokumen Resmi
        $sheet->setCellValue('A1', 'KEMENTERIAN AGAMA REPUBLIK INDONESIA');
        $sheet->setCellValue('A2', 'KANTOR KEMENTERIAN AGAMA KABUPATEN JEMBER');
        $sheet->setCellValue('A3', 'MADRASAH IBTIDAIYAH NEGERI 2 JEMBER (MIN 2 JEMBER)');
        $sheet->setCellValue('A4', 'LAPORAN REKAPITULASI PRESENSI SISWA BULANAN');
        
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + $numDays + 5);

        for ($r = 1; $r <= 4; $r++) {
            $sheet->mergeCells("A{$r}:{$lastColLetter}{$r}");
            $sheet->getStyle("A{$r}")->getFont()->setBold(true)->setSize($r === 3 ? 13 : ($r === 4 ? 12 : 11));
            $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Info Kelas & Periode
        $namaBulan = Carbon::create($data['tahun'], $data['bulan'], 1)->translatedFormat('F Y');
        $sheet->setCellValue('A6', "Kelas / Rombel : {$kelas->nama_kelas}");
        $sheet->setCellValue('A7', "Bulan / Tahun  : {$namaBulan}");
        $sheet->setCellValue('A8', "Wali Kelas     : " . ($kelas->waliKelas ? $kelas->waliKelas->name : '-'));
        $sheet->getStyle('A6:A8')->getFont()->setBold(true)->setSize(10);

        // Header Tabel
        $headerRow = 10;
        $sheet->setCellValue('A10', 'No');
        $sheet->setCellValue('B10', 'NIS');
        $sheet->setCellValue('C10', 'Nama Siswa');
        $sheet->setCellValue('D10', 'L/P');

        $colIdx = 5;
        foreach ($monthDates as $cDate) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $sheet->setCellValue("{$colLetter}10", $cDate->day);
            if ($cDate->isWeekend()) {
                $sheet->getStyle("{$colLetter}10")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
            }
            $colIdx++;
        }

        $colH = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx++);
        $colI = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx++);
        $colS = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx++);
        $colA = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx++);
        $colP = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx++);

        $sheet->setCellValue("{$colH}10", 'H');
        $sheet->setCellValue("{$colI}10", 'I');
        $sheet->setCellValue("{$colS}10", 'S');
        $sheet->setCellValue("{$colA}10", 'A');
        $sheet->setCellValue("{$colP}10", '%');

        // Style Header Tabel
        $lastDataCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx - 1);
        $headerRange = "A10:{$lastDataCol}10";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0E8345']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Isi Data Siswa
        $row = 11;
        foreach ($data['matrix'] as $index => $item) {
            $s = $item['siswa'];
            $sheet->setCellValue("A{$row}", $s->no_absen ?? ($index + 1));
            $sheet->setCellValue("B{$row}", $s->nis);
            $sheet->setCellValue("C{$row}", $s->nama_lengkap);
            $sheet->setCellValue("D{$row}", $s->jenis_kelamin);

            $cIdx = 5;
            foreach ($monthDates as $cDate) {
                $cLet = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx);
                $st = $item['daily'][$cDate->day]['status'];
                $isW = $item['daily'][$cDate->day]['is_weekend'];

                if ($isW) {
                    $sheet->setCellValue("{$cLet}{$row}", '-');
                    $sheet->getStyle("{$cLet}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
                } else {
                    $sym = $st ? strtoupper(substr($st, 0, 1)) : '.';
                    $sheet->setCellValue("{$cLet}{$row}", $sym);
                    if ($sym === 'H') {
                        $sheet->getStyle("{$cLet}{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0E8345'))->setBold(true);
                    } elseif ($sym === 'I') {
                        $sheet->getStyle("{$cLet}{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2563EB'))->setBold(true);
                    } elseif ($sym === 'S') {
                        $sheet->getStyle("{$cLet}{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('D97706'))->setBold(true);
                    } elseif ($sym === 'A') {
                        $sheet->getStyle("{$cLet}{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'))->setBold(true);
                    }
                }
                $sheet->getStyle("{$cLet}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $cIdx++;
            }

            $sheet->setCellValue("{$colH}{$row}", $item['hadir']);
            $sheet->setCellValue("{$colI}{$row}", $item['izin']);
            $sheet->setCellValue("{$colS}{$row}", $item['sakit']);
            $sheet->setCellValue("{$colA}{$row}", $item['alpa']);
            $sheet->setCellValue("{$colP}{$row}", $item['persentase'] . '%');

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("{$colH}{$row}:{$colP}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Border Seluruh Tabel
        $sheet->getStyle("A10:{$lastDataCol}" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);

        // Tanda Tangan Resmi
        $signRow = $row + 2;
        $signColKamad = 'C';
        $signColWali = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx - 4);

        $sheet->setCellValue("{$signColWali}{$signRow}", 'Jember, ' . Carbon::now()->translatedFormat('d F Y'));
        $sheet->setCellValue("{$signColKamad}" . ($signRow + 1), 'Mengetahui,');
        $sheet->setCellValue("{$signColKamad}" . ($signRow + 2), 'Kepala MIN 2 Jember');
        $sheet->setCellValue("{$signColWali}" . ($signRow + 2), 'Wali Kelas ' . $kelas->nama_kelas);

        $nameRow = $signRow + 6;
        $sheet->setCellValue("{$signColKamad}{$nameRow}", 'SITI FATHUNNURROHMIYATI, S. Ag.');
        $sheet->setCellValue("{$signColKamad}" . ($nameRow + 1), 'NIP. 197508122005012003');

        $sheet->setCellValue("{$signColWali}{$nameRow}", $kelas->waliKelas ? $kelas->waliKelas->name : '( ................................... )');
        $sheet->setCellValue("{$signColWali}" . ($nameRow + 1), 'NIP. ' . ($kelas->waliKelas && $kelas->waliKelas->nip ? $kelas->waliKelas->nip : '...............................'));

        $sheet->getStyle("{$signColKamad}{$nameRow}")->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle("{$signColWali}{$nameRow}")->getFont()->setBold(true)->setUnderline(true);

        // Auto size columns A to D
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $cleanKelasName = preg_replace('/[^A-Za-z0-9_-]/', '_', $kelas->nama_kelas);
        $fileName = "Rekap_Absensi_MIN2Jember_Kelas_{$cleanKelasName}_{$namaBulan}.xlsx";

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Export Rekap Semester ke Dokumen Excel (.xlsx)
     */
    public function exportSemesterExcel(array $data): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Semester');

        $kelas = $data['kelas'];
        $bulanList = $data['bulanList'];

        // Kop
        $sheet->setCellValue('A1', 'KEMENTERIAN AGAMA REPUBLIK INDONESIA');
        $sheet->setCellValue('A2', 'KANTOR KEMENTERIAN AGAMA KABUPATEN JEMBER');
        $sheet->setCellValue('A3', 'MADRASAH IBTIDAIYAH NEGERI 2 JEMBER (MIN 2 JEMBER)');
        $sheet->setCellValue('A4', 'LAPORAN REKAPITULASI PRESENSI ' . $data['labelSemester']);

        $totalCols = 4 + (count($bulanList) * 4) + 5;
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols);

        for ($r = 1; $r <= 4; $r++) {
            $sheet->mergeCells("A{$r}:{$lastColLetter}{$r}");
            $sheet->getStyle("A{$r}")->getFont()->setBold(true)->setSize($r === 3 ? 13 : ($r === 4 ? 12 : 11));
            $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Info
        $sheet->setCellValue('A6', "Kelas / Rombel : {$kelas->nama_kelas}");
        $sheet->setCellValue('A7', "Tahun Ajaran   : {$data['tahunAjaran']}");
        $sheet->setCellValue('A8', "Wali Kelas     : " . ($kelas->waliKelas ? $kelas->waliKelas->name : '-'));
        $sheet->getStyle('A6:A8')->getFont()->setBold(true)->setSize(10);

        // Header Tabel Multi-level
        $sheet->mergeCells('A10:A11');
        $sheet->mergeCells('B10:B11');
        $sheet->mergeCells('C10:C11');
        $sheet->mergeCells('D10:D11');

        $sheet->setCellValue('A10', 'No');
        $sheet->setCellValue('B10', 'NIS');
        $sheet->setCellValue('C10', 'Nama Siswa');
        $sheet->setCellValue('D10', 'L/P');

        $colIdx = 5;
        foreach ($bulanList as $b) {
            $startLet = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
            $endLet = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 3);
            $sheet->mergeCells("{$startLet}10:{$endLet}10");
            $sheet->setCellValue("{$startLet}10", $b['nama']);

            $sheet->setCellValue("{$startLet}11", 'H');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1) . '11', 'I');
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 2) . '11', 'S');
            $sheet->setCellValue("{$endLet}11", 'A');

            $colIdx += 4;
        }

        // Total Semester
        $totStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx);
        $totEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 4);
        $sheet->mergeCells("{$totStart}10:{$totEnd}10");
        $sheet->setCellValue("{$totStart}10", 'TOTAL SEMESTER');

        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx) . '11', 'H');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1) . '11', 'I');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 2) . '11', 'S');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 3) . '11', 'A');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 4) . '11', '%');

        // Style Header
        $sheet->getStyle("A10:{$totEnd}11")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0E8345']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Data Siswa
        $row = 12;
        foreach ($data['matrix'] as $index => $item) {
            $s = $item['siswa'];
            $sheet->setCellValue("A{$row}", $s->no_absen ?? ($index + 1));
            $sheet->setCellValue("B{$row}", $s->nis);
            $sheet->setCellValue("C{$row}", $s->nama_lengkap);
            $sheet->setCellValue("D{$row}", $s->jenis_kelamin);

            $cIdx = 5;
            foreach ($item['bulanBreakdown'] as $mb) {
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row, $mb['hadir']);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 1) . $row, $mb['izin']);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 2) . $row, $mb['sakit']);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 3) . $row, $mb['alpa']);
                $cIdx += 4;
            }

            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx) . $row, $item['totalH']);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 1) . $row, $item['totalI']);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 2) . $row, $item['totalS']);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 3) . $row, $item['totalA']);
            $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cIdx + 4) . $row, $item['persentase'] . '%');

            $sheet->getStyle("A{$row}:D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E{$row}:{$totEnd}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Border
        $sheet->getStyle("A10:{$totEnd}" . ($row - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ]);

        // Tanda tangan
        $signRow = $row + 2;
        $signColKamad = 'C';
        $signColWali = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCols - 3);

        $sheet->setCellValue("{$signColWali}{$signRow}", 'Jember, ' . Carbon::now()->translatedFormat('d F Y'));
        $sheet->setCellValue("{$signColKamad}" . ($signRow + 1), 'Mengetahui,');
        $sheet->setCellValue("{$signColKamad}" . ($signRow + 2), 'Kepala MIN 2 Jember');
        $sheet->setCellValue("{$signColWali}" . ($signRow + 2), 'Wali Kelas ' . $kelas->nama_kelas);

        $nameRow = $signRow + 6;
        $sheet->setCellValue("{$signColKamad}{$nameRow}", 'SITI FATHUNNURROHMIYATI, S. Ag.');
        $sheet->setCellValue("{$signColKamad}" . ($nameRow + 1), 'NIP. 197508122005012003');

        $sheet->setCellValue("{$signColWali}{$nameRow}", $kelas->waliKelas ? $kelas->waliKelas->name : '( ................................... )');
        $sheet->setCellValue("{$signColWali}" . ($nameRow + 1), 'NIP. ' . ($kelas->waliKelas && $kelas->waliKelas->nip ? $kelas->waliKelas->nip : '...............................'));

        $sheet->getStyle("{$signColKamad}{$nameRow}")->getFont()->setBold(true)->setUnderline(true);
        $sheet->getStyle("{$signColWali}{$nameRow}")->getFont()->setBold(true)->setUnderline(true);

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $cleanKelasName = preg_replace('/[^A-Za-z0-9_-]/', '_', $kelas->nama_kelas);
        $fileName = "Rekap_Semester_MIN2Jember_Kelas_{$cleanKelasName}_{$data['semester']}.xlsx";

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Export Rekap Bulanan ke PDF
     */
    public function exportMonthlyPdf(array $data)
    {
        $pdf = Pdf::loadView('exports.rekap_bulanan_pdf', $data)
            ->setPaper('a4', 'landscape');

        $cleanKelasName = preg_replace('/[^A-Za-z0-9_-]/', '_', $data['kelas']->nama_kelas);
        $fileName = "Rekap_Absensi_MIN2Jember_Kelas_{$cleanKelasName}_Bulan_{$data['bulan']}_{$data['tahun']}.pdf";

        return $pdf->download($fileName);
    }

    /**
     * Export Rekap Semester ke PDF
     */
    public function exportSemesterPdf(array $data)
    {
        $pdf = Pdf::loadView('exports.rekap_semester_pdf', $data)
            ->setPaper('a4', 'landscape');

        $cleanKelasName = preg_replace('/[^A-Za-z0-9_-]/', '_', $data['kelas']->nama_kelas);
        $fileName = "Rekap_Semester_MIN2Jember_Kelas_{$cleanKelasName}_{$data['semester']}.pdf";

        return $pdf->download($fileName);
    }
}
