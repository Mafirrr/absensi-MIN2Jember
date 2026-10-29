<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Kehadiran Harian - {{ $tanggal }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 3px 0; font-size: 12px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #777; padding: 6px 8px; }
        th { background-color: #f0f0f0; text-align: left; font-size: 11px; }
        .text-center { text-align: center; }
        .summary-box { display: flex; gap: 15px; margin-bottom: 15px; font-weight: bold; }
        .signature-area { margin-top: 40px; display: flex; justify-content: space-between; }
        .sig-block { text-align: center; width: 200px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
        <span style="margin-left: 10px; color: #666;">Tekan tombol di atas untuk mencetak dokumen.</span>
    </div>

    <div class="header">
        <h1>LAPORAN KEHADIRAN SISWA HARIAN</h1>
        <p>Sistem Informasi Absensi Digital (SIABSEN)</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }} | <strong>Kelas:</strong> {{ $selectedKelas ? $selectedKelas->nama_kelas : 'Semua Rombel' }}</p>
    </div>

    <div class="summary-box">
        <div>Total Siswa: {{ $totalSiswaTercatat }}</div>
        <div>Hadir (H): {{ $totalHadir }}</div>
        <div>Izin (I): {{ $totalIzin }}</div>
        <div>Sakit (S): {{ $totalSakit }}</div>
        <div>Alpa (A): {{ $totalAlpa }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th style="width: 80px;">NIS</th>
                <th>Nama Lengkap Siswa</th>
                <th style="width: 90px;">Kelas</th>
                <th class="text-center" style="width: 70px;">Status</th>
                <th>Keterangan / Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensiList as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $item->siswa->nis }}</td>
                    <td>{{ $item->siswa->nama_lengkap }}</td>
                    <td>{{ $item->kelas->nama_kelas }}</td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ strtoupper($item->status) }}
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-area">
        <div class="sig-block">
            <p>Mengetahui,<br>Kepala Tata Usaha</p>
            <br><br><br>
            <p><strong>( ..................................... )</strong><br>NIP: ..............................</p>
        </div>
        <div class="sig-block">
            <p>Dicetak Pada: {{ now()->translatedFormat('d F Y') }}<br>Petugas TU</p>
            <br><br><br>
            <p><strong>{{ auth()->user()->name }}</strong><br>NIP: {{ auth()->user()->nip ?? '-' }}</p>
        </div>
    </div>
</body>
</html>

