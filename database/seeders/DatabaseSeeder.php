<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (TU & Guru)
        $tu = User::create([
            'name' => 'Budi Santoso, S.Kom (Tata Usaha)',
            'email' => 'tu@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'tu',
            'nip' => '198501152010011002',
            'phone' => '081234567890',
            'gender' => 'L',
        ]);

        $guru1 = User::create([
            'name' => 'Siti Rahmawati, S.Pd (Wali Kelas X RPL 1)',
            'email' => 'guru@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '198804222014022001',
            'phone' => '081234567891',
            'gender' => 'P',
        ]);

        $guru2 = User::create([
            'name' => 'Ahmad Fauzi, M.Kom (Wali Kelas XI RPL 1)',
            'email' => 'fauzi@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '199003152018011003',
            'phone' => '081234567892',
            'gender' => 'L',
        ]);

        $guru3 = User::create([
            'name' => 'Dewi Lestari, S.Pd (Wali Kelas XII TKJ 1)',
            'email' => 'dewi@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'nip' => '199207082019032004',
            'phone' => '081234567893',
            'gender' => 'P',
        ]);

        // 2. Seed Kelas
        $kelas1 = Kelas::create([
            'nama_kelas' => 'X RPL 1',
            'tingkat' => '10',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'wali_kelas_id' => $guru1->id,
            'tahun_ajaran' => '2025/2026',
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => '11',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'wali_kelas_id' => $guru2->id,
            'tahun_ajaran' => '2025/2026',
        ]);

        $kelas3 = Kelas::create([
            'nama_kelas' => 'XII TKJ 1',
            'tingkat' => '12',
            'jurusan' => 'Teknik Komputer & Jaringan',
            'wali_kelas_id' => $guru3->id,
            'tahun_ajaran' => '2025/2026',
        ]);

        $kelas4 = Kelas::create([
            'nama_kelas' => 'X TKJ 2',
            'tingkat' => '10',
            'jurusan' => 'Teknik Komputer & Jaringan',
            'wali_kelas_id' => null,
            'tahun_ajaran' => '2025/2026',
        ]);

        // Guru 1 juga mengajar di kelas 4
        $guru1->kelasAjar()->attach($kelas4->id, ['mata_pelajaran' => 'Dasar Pemrograman']);

        // 3. Seed Siswa untuk Kelas X RPL 1
        $siswaData = [
            ['nis' => '1001', 'nama' => 'Aditya Pratama', 'jk' => 'L'],
            ['nis' => '1002', 'nama' => 'Anisa Putri Maharani', 'jk' => 'P'],
            ['nis' => '1003', 'nama' => 'Bayu Arya Nugraha', 'jk' => 'L'],
            ['nis' => '1004', 'nama' => 'Cantika Dewi', 'jk' => 'P'],
            ['nis' => '1005', 'nama' => 'Daffa Maulana', 'jk' => 'L'],
            ['nis' => '1006', 'nama' => 'Eka Safitri', 'jk' => 'P'],
            ['nis' => '1007', 'nama' => 'Fajar Hidayat', 'jk' => 'L'],
            ['nis' => '1008', 'nama' => 'Gita Permata', 'jk' => 'P'],
            ['nis' => '1009', 'nama' => 'Hendra Kusuma', 'jk' => 'L'],
            ['nis' => '1010', 'nama' => 'Indah Cahyani', 'jk' => 'P'],
            ['nis' => '1011', 'nama' => 'Joko Setiawan', 'jk' => 'L'],
            ['nis' => '1012', 'nama' => 'Karin Nathania', 'jk' => 'P'],
        ];

        $createdSiswaX = [];
        foreach ($siswaData as $index => $item) {
            $siswa = Siswa::create([
                'nis' => $item['nis'],
                'nisn' => '0089' . str_pad($item['nis'], 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelas1->id,
                'no_absen' => $index + 1,
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2009-05-12',
                'alamat' => 'Jl. Merdeka No. ' . ($index + 10) . ', Jakarta',
                'no_telepon_ortu' => '0813998877' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'aktif',
            ]);
            $createdSiswaX[] = $siswa;
        }

        // Siswa untuk Kelas XI RPL 1
        $siswaDataXI = [
            ['nis' => '2001', 'nama' => 'Bintang Ramadhan', 'jk' => 'L'],
            ['nis' => '2002', 'nama' => 'Clarissa Aurelia', 'jk' => 'P'],
            ['nis' => '2003', 'nama' => 'Dimas Anggara', 'jk' => 'L'],
            ['nis' => '2004', 'nama' => 'Farhan Syahputra', 'jk' => 'L'],
            ['nis' => '2005', 'nama' => 'Nabila Syakieb', 'jk' => 'P'],
            ['nis' => '2006', 'nama' => 'Rezky Aditya', 'jk' => 'L'],
        ];

        $createdSiswaXI = [];
        foreach ($siswaDataXI as $index => $item) {
            $siswa = Siswa::create([
                'nis' => $item['nis'],
                'nisn' => '0078' . str_pad($item['nis'], 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelas2->id,
                'no_absen' => $index + 1,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-03-20',
                'alamat' => 'Jl. Anggrek No. ' . ($index + 5) . ', Jakarta',
                'no_telepon_ortu' => '0813776655' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'aktif',
            ]);
            $createdSiswaXI[] = $siswa;
        }

        // Siswa untuk Kelas XII TKJ 1
        $siswaDataXII = [
            ['nis' => '3001', 'nama' => 'Aliando Syarief', 'jk' => 'L'],
            ['nis' => '3002', 'nama' => 'Chelsea Islan', 'jk' => 'P'],
            ['nis' => '3003', 'nama' => 'Dwi Sasono', 'jk' => 'L'],
            ['nis' => '3004', 'nama' => 'Maudy Ayunda', 'jk' => 'P'],
        ];

        $createdSiswaXII = [];
        foreach ($siswaDataXII as $index => $item) {
            $siswa = Siswa::create([
                'nis' => $item['nis'],
                'nisn' => '0067' . str_pad($item['nis'], 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelas3->id,
                'no_absen' => $index + 1,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2007-09-14',
                'alamat' => 'Jl. Melati No. ' . ($index + 8) . ', Jakarta',
                'no_telepon_ortu' => '0813443322' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'aktif',
            ]);
            $createdSiswaXII[] = $siswa;
        }

        // Siswa untuk Kelas X TKJ 2
        $siswaDataX2 = [
            ['nis' => '4001', 'nama' => 'Bima Arya', 'jk' => 'L'],
            ['nis' => '4002', 'nama' => 'Citra Kirana', 'jk' => 'P'],
            ['nis' => '4003', 'nama' => 'Danang Prasetyo', 'jk' => 'L'],
            ['nis' => '4004', 'nama' => 'Erina Gudono', 'jk' => 'P'],
        ];

        $createdSiswaX2 = [];
        foreach ($siswaDataX2 as $index => $item) {
            $siswa = Siswa::create([
                'nis' => $item['nis'],
                'nisn' => '0068' . str_pad($item['nis'], 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelas4->id,
                'no_absen' => $index + 1,
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2008-11-05',
                'alamat' => 'Jl. Kenanga No. ' . ($index + 12) . ', Jakarta',
                'no_telepon_ortu' => '0813998877' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'status' => 'aktif',
            ]);
            $createdSiswaX2[] = $siswa;
        }

        // 4. Seed Absensi 14 hari terakhir sampai hari ini
        $today = Carbon::today();
        for ($d = 13; $d >= 0; $d--) {
            $tgl = $today->copy()->subDays($d);
            // Lewati hari Sabtu dan Minggu (libur)
            if ($tgl->isWeekend()) {
                continue;
            }

            $dateStr = $tgl->format('Y-m-d');

            // Absensi Kelas X RPL 1
            foreach ($createdSiswaX as $idx => $s) {
                $status = 'hadir';
                $catatan = null;

                if ($idx === 3 && $d % 4 === 0) {
                    $status = 'sakit';
                    $catatan = 'Demam dan flu';
                } elseif ($idx === 6 && $d % 5 === 0) {
                    $status = 'izin';
                    $catatan = 'Ada acara keluarga';
                } elseif ($idx === 10 && $d === 1) {
                    $status = 'alpa';
                    $catatan = 'Tanpa keterangan';
                } elseif ($idx === 1 && $d === 0) {
                    $status = 'izin';
                    $catatan = 'Izin keperluan keluarga';
                }

                Absensi::create([
                    'siswa_id' => $s->id,
                    'kelas_id' => $kelas1->id,
                    'tanggal' => $dateStr,
                    'status' => $status,
                    'catatan' => $catatan,
                    'guru_id' => $guru1->id,
                ]);
            }

            // Absensi Kelas XI RPL 1
            foreach ($createdSiswaXI as $idx => $s) {
                $status = 'hadir';
                $catatan = null;

                if ($idx === 2 && $d % 3 === 0) {
                    $status = 'izin';
                    $catatan = 'Mengurus berkas lomba';
                } elseif ($idx === 4 && $d === 0) {
                    $status = 'sakit';
                    $catatan = 'Sakit radang';
                }

                Absensi::create([
                    'siswa_id' => $s->id,
                    'kelas_id' => $kelas2->id,
                    'tanggal' => $dateStr,
                    'status' => $status,
                    'catatan' => $catatan,
                    'guru_id' => $guru2->id,
                ]);
            }

            // Absensi Kelas XII TKJ 1
            foreach ($createdSiswaXII as $idx => $s) {
                $status = 'hadir';
                $catatan = null;

                if ($idx === 1 && $d % 4 === 0) {
                    $status = 'izin';
                    $catatan = 'Pemeriksaan kesehatan';
                } elseif ($idx === 2 && $d === 2) {
                    $status = 'sakit';
                    $catatan = 'Demam';
                }

                Absensi::create([
                    'siswa_id' => $s->id,
                    'kelas_id' => $kelas3->id,
                    'tanggal' => $dateStr,
                    'status' => $status,
                    'catatan' => $catatan,
                    'guru_id' => $guru3->id,
                ]);
            }

            // Absensi Kelas X TKJ 2
            foreach ($createdSiswaX2 as $idx => $s) {
                $status = 'hadir';
                $catatan = null;

                if ($idx === 0 && $d % 3 === 0) {
                    $status = 'izin';
                    $catatan = 'Urusan keluarga';
                }

                Absensi::create([
                    'siswa_id' => $s->id,
                    'kelas_id' => $kelas4->id,
                    'tanggal' => $dateStr,
                    'status' => $status,
                    'catatan' => $catatan,
                    'guru_id' => $guru1->id,
                ]);
            }
        }
    }
}
