<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaImportService
{
    /**
     * Parse multi-line text input into student records.
     * Format per line: NIS, NISN, Nama Lengkap, JK (L/P), No HP Ortu (opsional)
     * Pemisah bisa berupa koma (,), titik koma (;), atau tab (\t)
     */
    public function importFromText(string $rawText, int $kelasId): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($rawText));
        $successCount = 0;
        $errors = [];
        $existingCount = 0;

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Deteksi pemisah: tab, titik koma, atau koma
            if (str_contains($line, "\t")) {
                $parts = explode("\t", $line);
            } elseif (str_contains($line, ";")) {
                $parts = explode(";", $line);
            } else {
                $parts = explode(",", $line);
            }

            $parts = array_map('trim', $parts);

            // Minimal butuh NIS dan Nama
            if (count($parts) < 2) {
                $errors[] = "Baris " . ($lineIndex + 1) . ": Format tidak valid (minimal NIS dan Nama Lengkap).";
                continue;
            }

            $nis = $parts[0] ?? '';
            $nisn = !empty($parts[1]) ? $parts[1] : null;
            $nama = $parts[2] ?? ($parts[1] ?? ''); // jika 2 kolom: NIS, Nama
            if (count($parts) === 2) {
                $nisn = null;
                $nama = $parts[1];
            }

            $jk = 'L';
            if (isset($parts[3])) {
                $rawJk = strtoupper($parts[3]);
                $jk = in_array($rawJk, ['P', 'PEREMPUAN', 'F']) ? 'P' : 'L';
            } elseif (count($parts) === 3 && in_array(strtoupper($parts[2]), ['L', 'P'])) {
                // Format: NIS, Nama, JK
                $jk = strtoupper($parts[2]);
                $nama = $parts[1];
                $nisn = null;
            }

            $telepon = $parts[4] ?? ($parts[3] ?? null);
            if ($telepon && !preg_match('/^[0-9+\-\s]+$/', $telepon)) {
                $telepon = null;
            }

            if (empty($nis) || empty($nama)) {
                $errors[] = "Baris " . ($lineIndex + 1) . ": NIS atau Nama tidak boleh kosong.";
                continue;
            }

            // Cek apakah NIS sudah ada
            if (Siswa::where('nis', $nis)->exists()) {
                $existingCount++;
                continue;
            }

            // Tentukan nomor absen otomatis
            $nextAbsen = Siswa::where('kelas_id', $kelasId)->max('no_absen') + 1;

            Siswa::create([
                'nis' => $nis,
                'nisn' => $nisn,
                'nama_lengkap' => $nama,
                'jenis_kelamin' => $jk,
                'kelas_id' => $kelasId,
                'no_absen' => $nextAbsen,
                'no_telepon_ortu' => $telepon,
                'status' => 'aktif',
            ]);

            $successCount++;
        }

        return [
            'success_count' => $successCount,
            'existing_count' => $existingCount,
            'errors' => $errors,
        ];
    }

    /**
     * Import students from an uploaded Excel / CSV file
     */
    public function importFromFile(UploadedFile $file, int $kelasId): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (empty($rows)) {
            return [
                'success_count' => 0,
                'existing_count' => 0,
                'errors' => ['File kosong atau format tidak terbaca.'],
            ];
        }

        $successCount = 0;
        $existingCount = 0;
        $errors = [];

        // Lewati header (baris 1) jika berisi judul seperti "NIS", "NAMA", dll
        $startIndex = 0;
        $firstCell = strtolower(trim((string) ($rows[0][0] ?? '')));
        if (str_contains($firstCell, 'nis') || str_contains($firstCell, 'no') || str_contains($firstCell, 'nama')) {
            $startIndex = 1;
        }

        for ($i = $startIndex; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nis = trim((string) ($row[0] ?? ''));
            $nisn = !empty($row[1]) ? trim((string) $row[1]) : null;
            $nama = trim((string) ($row[2] ?? ''));
            $jkRaw = strtoupper(trim((string) ($row[3] ?? 'L')));
            $jk = in_array($jkRaw, ['P', 'PEREMPUAN', 'F']) ? 'P' : 'L';
            $telepon = !empty($row[4]) ? trim((string) $row[4]) : null;

            if (empty($nis) && empty($nama)) {
                continue;
            }

            if (empty($nis) || empty($nama)) {
                $errors[] = "Baris " . ($i + 1) . ": NIS atau Nama kosong.";
                continue;
            }

            if (Siswa::where('nis', $nis)->exists()) {
                $existingCount++;
                continue;
            }

            $nextAbsen = Siswa::where('kelas_id', $kelasId)->max('no_absen') + 1;

            Siswa::create([
                'nis' => $nis,
                'nisn' => $nisn,
                'nama_lengkap' => $nama,
                'jenis_kelamin' => $jk,
                'kelas_id' => $kelasId,
                'no_absen' => $nextAbsen,
                'no_telepon_ortu' => $telepon,
                'status' => 'aktif',
            ]);

            $successCount++;
        }

        return [
            'success_count' => $successCount,
            'existing_count' => $existingCount,
            'errors' => $errors,
        ];
    }

    /**
     * Generate sample Excel template for student bulk import
     */
    public function generateTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        // Headers
        $headers = ['NIS', 'NISN', 'Nama Lengkap', 'Jenis Kelamin (L/P)', 'No HP Ortu'];
        $sheet->fromArray($headers, null, 'A1');

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0E8345'], // Hijau Madrasah
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Contoh Data
        $samples = [
            ['1001', '0015100101', 'Ahmad Dani Pratama', 'L', '081234567891'],
            ['1002', '0015100102', 'Aisyah Putri Azzahra', 'P', '081234567892'],
            ['1003', '0015100103', 'Bilal Habiburrahman', 'L', '081234567893'],
        ];
        $sheet->fromArray($samples, null, 'A2');

        // Auto width
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $fileName = 'Template_Import_Siswa_MIN2Jember.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
