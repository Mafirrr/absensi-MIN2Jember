<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Semester - MIN 2 Jember</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm 15mm 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #1e293b;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .header-kop h1 {
            font-size: 11pt;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header-kop h2 {
            font-size: 13pt;
            margin: 2px 0;
            font-weight: 900;
            color: #0E8345;
        }
        .header-kop p {
            font-size: 7.5pt;
            margin: 0;
            color: #475569;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 8px;
            font-size: 8.5pt;
        }
        .meta-info td {
            padding: 1px 4px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #94a3b8;
            padding: 3px 2px;
            text-align: center;
        }
        table.data-table th {
            background-color: #0E8345;
            color: #ffffff;
            font-weight: bold;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .signature-table {
            width: 100%;
            margin-top: 15px;
            font-size: 8.5pt;
        }
        .signature-table td {
            vertical-align: top;
            width: 50%;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Madrasah -->
    <div class="header-kop">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <p>KANTOR KEMENTERIAN AGAMA KABUPATEN JEMBER</p>
        <h2>MADRASAH IBTIDAIYAH NEGERI 2 JEMBER</h2>
        <p>Alamat: Jl. KH. Wahid Hasyim No. 65 Jember, Jawa Timur &bull; Website: min2jember.sch.id &bull; Telp: (0331) 487212</p>
    </div>

    <div style="text-align: center; margin-bottom: 8px;">
        <span style="font-size: 10pt; font-weight: bold; text-decoration: underline;">LAPORAN REKAPITULASI PRESENSI {{ $labelSemester }}</span>
    </div>

    <!-- Info Kelas & Periode -->
    <table class="meta-info">
        <tr>
            <td style="width: 15%; font-weight: bold;">Rombel / Kelas</td>
            <td style="width: 35%;">: <strong>Kelas {{ $kelas->nama_kelas }}</strong> (Tingkat {{ $kelas->tingkat }})</td>
            <td style="width: 15%; font-weight: bold;">Tahun Ajaran</td>
            <td style="width: 35%;">: <strong>{{ $tahunAjaran }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Wali Kelas</td>
            <td>: {{ $kelas->waliKelas ? $kelas->waliKelas->name : '-' }}</td>
            <td style="font-weight: bold;">Semester</td>
            <td>: {{ ucfirst($semester) }}</td>
        </tr>
    </table>

    <!-- Tabel Matriks Semester -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20px;" rowspan="2">No</th>
                <th style="width: 40px;" rowspan="2">NIS</th>
                <th style="width: 130px;" rowspan="2">Nama Siswa</th>
                <th style="width: 20px;" rowspan="2">JK</th>
                @foreach($bulanList as $b)
                    <th colspan="4">{{ $b['nama'] }}</th>
                @endforeach
                <th colspan="4" style="background-color: #064e3b;">TOTAL SEMESTER</th>
                <th style="width: 30px;" rowspan="2">%</th>
            </tr>
            <tr>
                @foreach($bulanList as $b)
                    <th style="width: 15px; font-size: 6.5pt; background-color: #065f46;">H</th>
                    <th style="width: 15px; font-size: 6.5pt; background-color: #1e40af;">I</th>
                    <th style="width: 15px; font-size: 6.5pt; background-color: #b45309;">S</th>
                    <th style="width: 15px; font-size: 6.5pt; background-color: #991b1b;">A</th>
                @endforeach
                <th style="width: 18px; font-size: 7pt; background-color: #065f46;">H</th>
                <th style="width: 18px; font-size: 7pt; background-color: #1e40af;">I</th>
                <th style="width: 18px; font-size: 7pt; background-color: #b45309;">S</th>
                <th style="width: 18px; font-size: 7pt; background-color: #991b1b;">A</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matrix as $index => $item)
                @php $s = $item['siswa']; @endphp
                <tr>
                    <td>{{ $s->no_absen ?? ($index + 1) }}</td>
                    <td style="font-family: monospace;">{{ $s->nis }}</td>
                    <td class="text-left" style="padding-left: 4px; white-space: nowrap;">{{ $s->nama_lengkap }}</td>
                    <td>{{ $s->jenis_kelamin }}</td>

                    @foreach($item['bulanBreakdown'] as $mb)
                        <td>{{ $mb['hadir'] }}</td>
                        <td>{{ $mb['izin'] }}</td>
                        <td>{{ $mb['sakit'] }}</td>
                        <td>{{ $mb['alpa'] }}</td>
                    @endforeach

                    <td class="font-bold" style="color: #0E8345;">{{ $item['totalH'] }}</td>
                    <td class="font-bold" style="color: #2563eb;">{{ $item['totalI'] }}</td>
                    <td class="font-bold" style="color: #d97706;">{{ $item['totalS'] }}</td>
                    <td class="font-bold" style="color: #dc2626;">{{ $item['totalA'] }}</td>
                    <td class="font-bold">{{ $item['persentase'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 4 + (count($bulanList) * 4) + 5 }}" style="padding: 10px;">Belum ada data siswa di kelas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td style="padding-left: 30px;">
                <p>Mengetahui,</p>
                <p>Kepala MIN 2 Jember</p>
                <div style="height: 45px;"></div>
                <p style="font-weight: bold; text-decoration: underline;">SITI FATHUNNURROHMIYATI, S. Ag.</p>
                <p>NIP. 197508122005012003</p>
            </td>
            <td style="padding-left: 100px;">
                <p>Jember, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Wali Kelas {{ $kelas->nama_kelas }}</p>
                <div style="height: 45px;"></div>
                <p style="font-weight: bold; text-decoration: underline;">{{ $kelas->waliKelas ? $kelas->waliKelas->name : '( .......................................... )' }}</p>
                <p>NIP. {{ $kelas->waliKelas && $kelas->waliKelas->nip ? $kelas->waliKelas->nip : '...........................................' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
