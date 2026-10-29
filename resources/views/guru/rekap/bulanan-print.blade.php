<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran Bulanan - Kelas {{ $selectedKelas ? $selectedKelas->nama_kelas : '' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #222; margin: 15px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #666; padding: 4px 3px; text-align: center; }
        th { background-color: #f2f2f2; font-size: 9px; }
        .text-left { text-align: left; }
        .weekend { background-color: #e5e7eb; color: #9ca3af; }
        .signature-area { margin-top: 35px; display: flex; justify-content: space-between; }
        .sig-block { text-align: center; width: 220px; font-size: 11px; }
        @media print {
            @page { size: landscape; margin: 10mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #059669; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak Lembar Presensi (Landscape)
        </button>
        <span style="margin-left: 10px; color: #666;">Gunakan orientasi Landscape saat mencetak tabel ini.</span>
    </div>

    <div class="header">
        <h1>REKAPITULASI KEHADIRAN SISWA BULANAN</h1>
        <p>Kelas: {{ $selectedKelas ? $selectedKelas->nama_kelas : '-' }}</p>
        <p>
            <strong>Bulan / Tahun:</strong> {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }} | 
            <strong>Wali Kelas:</strong> {{ auth()->user()->name }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th class="text-left" style="width: 150px;">Nama Siswa</th>
                @foreach($monthDates as $date)
                    <th style="width: 18px;" class="{{ $date->isWeekend() ? 'weekend' : '' }}">{{ $date->day }}</th>
                @endforeach
                <th style="width: 25px; background: #dcfce7;">H</th>
                <th style="width: 25px; background: #dbeafe;">I</th>
                <th style="width: 25px; background: #fef3c7;">S</th>
                <th style="width: 25px; background: #fee2e2;">A</th>
                <th style="width: 35px; background: #f3f4f6;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrix as $idx => $row)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="text-left">{{ $row['siswa']->nama_lengkap }}</td>
                    @foreach($monthDates as $date)
                        @php
                            $st = $row['daily'][$date->day]['status'];
                            $isW = $row['daily'][$date->day]['is_weekend'];
                        @endphp
                        <td class="{{ $isW ? 'weekend' : '' }}">
                            @if($st === 'hadir') H
                            @elseif($st === 'izin') I
                            @elseif($st === 'sakit') S
                            @elseif($st === 'alpa') A
                            @else -
                            @endif
                        </td>
                    @endforeach
                    <td style="font-weight: bold; background: #f0fdf4;">{{ $row['h'] }}</td>
                    <td style="font-weight: bold; background: #eff6ff;">{{ $row['i'] }}</td>
                    <td style="font-weight: bold; background: #fffbeb;">{{ $row['s'] }}</td>
                    <td style="font-weight: bold; background: #fef2f2;">{{ $row['a'] }}</td>
                    <td style="font-weight: bold;">{{ $row['persen'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-area">
        <div class="sig-block">
            <p>Mengetahui,<br>Kepala Sekolah / TU</p>
            <br><br><br>
            <p><strong>( ..................................... )</strong><br>NIP: ..............................</p>
        </div>
        <div class="sig-block">
            <p>Wali Kelas / Guru Pengampu</p>
            <br><br><br>
            <p><strong>{{ auth()->user()->name }}</strong><br>NIP: {{ auth()->user()->nip ?? '..............................' }}</p>
        </div>
    </div>
</body>
</html>

