<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class Min2JemberSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Absensi::truncate();
        DB::table('guru_kelas')->truncate();
        Siswa::truncate();
        Kelas::truncate();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $defaultPassword = Hash::make('password123');

        // 1. Akun TU & Kepala Madrasah
        $tu = User::create([
            'name' => 'Budi Santoso, S.Kom (Tata Usaha)',
            'email' => 'tu@sekolah.sch.id',
            'password' => $defaultPassword,
            'role' => 'tu',
            'jenis_guru' => 'guru_kelas',
            'nip' => '198501152010011002',
            'phone' => '081234567890',
            'gender' => 'L',
        ]);

        $kamad = User::create([
            'name' => 'Siti Fathunnurrohmiyati, S. Ag. (Kepala Madrasah)',
            'email' => 'kamad@sekolah.sch.id',
            'password' => $defaultPassword,
            'role' => 'tu',
            'jenis_guru' => 'guru_kelas',
            'nip' => '197508122005012003',
            'phone' => '081234567899',
            'gender' => 'P',
        ]);

        // 2. Daftar 28 Guru Kelas (Wali Kelas) & Rombel MIN 2 Jember
        $rombelData = [
            // Kelas I
            ['tingkat' => '1', 'kode' => 'IA', 'nama' => 'I Sunan Ampel', 'guru' => 'Husnul Hotimah, S.Pd.I.', 'email' => 'husnul.hotimah@sekolah.sch.id', 'nip' => '198205102009012001', 'jk' => 'P'],
            ['tingkat' => '1', 'kode' => 'IB', 'nama' => 'I Sunan Bonang', 'guru' => 'Khusnaliyah, S.Pd.I.', 'email' => 'khusnaliyah@sekolah.sch.id', 'nip' => '198308152010012002', 'jk' => 'P'],
            ['tingkat' => '1', 'kode' => 'IC', 'nama' => 'I Sunan Kalijaga', 'guru' => 'Ahmad Syaikhuna Siddik, S.Pd.', 'email' => 'syaikhuna@sekolah.sch.id', 'nip' => '198904202014021003', 'jk' => 'L'],
            ['tingkat' => '1', 'kode' => 'ID', 'nama' => 'I Sunan Muria', 'guru' => 'Istiqomah, S. Pd.I.', 'email' => 'istiqomah@sekolah.sch.id', 'nip' => '198611122011012004', 'jk' => 'P'],
            ['tingkat' => '1', 'kode' => 'IE', 'nama' => 'I MM Ibrahim', 'guru' => 'Nur Fadlilah, S.Pd', 'email' => 'nurfadlilah@sekolah.sch.id', 'nip' => '198709182012022005', 'jk' => 'P'],
            ['tingkat' => '1', 'kode' => 'IF', 'nama' => 'I Sunan Gunung Jati', 'guru' => "Ni'matur Rohmah, S.Pd", 'email' => 'nimatur@sekolah.sch.id', 'nip' => '199003252015032006', 'jk' => 'P'],
            ['tingkat' => '1', 'kode' => 'IG', 'nama' => 'I Sunan Kudus', 'guru' => 'Nurul Hidayah, S.Pd.I', 'email' => 'nurulhidayah@sekolah.sch.id', 'nip' => '199107142016042007', 'jk' => 'P'],

            // Kelas II
            ['tingkat' => '2', 'kode' => 'IIA', 'nama' => 'II Ki Hajar Dewantara', 'guru' => 'Siti Khusnul Khotimah, S.Pd.I.', 'email' => 'sitikhusnul@sekolah.sch.id', 'nip' => '198402172008012008', 'jk' => 'P'],
            ['tingkat' => '2', 'kode' => 'IIB', 'nama' => 'II Pangeran Diponegoro', 'guru' => 'Muhammad Amin Sulhan, S.Pd', 'email' => 'aminsulhan@sekolah.sch.id', 'nip' => '198806222014021009', 'jk' => 'L'],
            ['tingkat' => '2', 'kode' => 'IIC', 'nama' => 'II Ir. Soekarno', 'guru' => 'Husnul Yaqin, S.Pd.I.', 'email' => 'husnulyaqin@sekolah.sch.id', 'nip' => '198909152015031010', 'jk' => 'L'],
            ['tingkat' => '2', 'kode' => 'IID', 'nama' => 'II Jenderal Sudirman', 'guru' => 'Moh. Roby Darmawan, S.Pd.SD', 'email' => 'robydarmawan@sekolah.sch.id', 'nip' => '199201102019031011', 'jk' => 'L'],
            ['tingkat' => '2', 'kode' => 'IIE', 'nama' => 'II Teuku Umar', 'guru' => 'Santi Budi Mirahati, S. Pd', 'email' => 'santibudi@sekolah.sch.id', 'nip' => '198505082010012012', 'jk' => 'P'],
            ['tingkat' => '2', 'kode' => 'IIF', 'nama' => 'II Moh. Hatta', 'guru' => 'Dewi Masita, S.Pd.I.', 'email' => 'dewimasita@sekolah.sch.id', 'nip' => '199304192019032013', 'jk' => 'P'],

            // Kelas III
            ['tingkat' => '3', 'kode' => 'IIIA', 'nama' => 'III Sunan Giri', 'guru' => 'Muhammad Hadi Susilo, S.Pd.', 'email' => 'hadisusilo@sekolah.sch.id', 'nip' => '198801202012021014', 'jk' => 'L'],
            ['tingkat' => '3', 'kode' => 'IIIB', 'nama' => 'III Sunan Drajat', 'guru' => 'Siti Sulaika, S.Pd.', 'email' => 'sitisulaika@sekolah.sch.id', 'nip' => '198703142011012015', 'jk' => 'P'],
            ['tingkat' => '3', 'kode' => 'IIIC', 'nama' => 'III Wali Songo', 'guru' => 'Siti Hotijah, S.Pd.', 'email' => 'sitihotijah@sekolah.sch.id', 'nip' => '198908232014022016', 'jk' => 'P'],
            ['tingkat' => '3', 'kode' => 'IIID', 'nama' => 'III Hassanudin', 'guru' => 'Moh.Ridwan, S.Pd, M.Pd.I', 'email' => 'mohridwan@sekolah.sch.id', 'nip' => '198305112009011017', 'jk' => 'L'],
            ['tingkat' => '3', 'kode' => 'IIIE', 'nama' => 'III Dr. Soetomo', 'guru' => 'Khusnul Kholilah, S.Pd.I', 'email' => 'khusnulkholilah@sekolah.sch.id', 'nip' => '199011152016042018', 'jk' => 'P'],

            // Kelas IV
            ['tingkat' => '4', 'kode' => 'IVA', 'nama' => 'IV Fatahillah', 'guru' => 'Ani Purwatiningsih, S.Pd.', 'email' => 'anipurwati@sekolah.sch.id', 'nip' => '198606272010012019', 'jk' => 'P'],
            ['tingkat' => '4', 'kode' => 'IVB', 'nama' => 'IV Imam Bonjol', 'guru' => 'Darmanto, S.Pd.I', 'email' => 'darmanto@sekolah.sch.id', 'nip' => '198409192008011020', 'jk' => 'L'],
            ['tingkat' => '4', 'kode' => 'IVC', 'nama' => 'IV Pangeran Antasari', 'guru' => 'S. Anik Andriyani, S.Ag.', 'email' => 'anika@sekolah.sch.id', 'nip' => '198007122005012021', 'jk' => 'P'],

            // Kelas V
            ['tingkat' => '5', 'kode' => 'VA', 'nama' => 'V Kartini', 'guru' => 'Syaifulloh, S. Pd. M. Pd. I', 'email' => 'syaifulloh@sekolah.sch.id', 'nip' => '198103042006041022', 'jk' => 'L'],
            ['tingkat' => '5', 'kode' => 'VB', 'nama' => 'V Cut Muti\'a', 'guru' => 'Moh. Khoiri, S. Pd. I', 'email' => 'mohkhoiri@sekolah.sch.id', 'nip' => '198512102010011023', 'jk' => 'L'],
            ['tingkat' => '5', 'kode' => 'VC', 'nama' => 'V Cut Nya\' Dien', 'guru' => 'Sri Kustatik, S.Pd.', 'email' => 'srikustatik@sekolah.sch.id', 'nip' => '198210082007012024', 'jk' => 'P'],
            ['tingkat' => '5', 'kode' => 'VD', 'nama' => 'V Dewi Sartika', 'guru' => 'Lutfiah Saadah, S.Pd.', 'email' => 'lutfiah@sekolah.sch.id', 'nip' => '199104162016042025', 'jk' => 'P'],

            // Kelas VI
            ['tingkat' => '6', 'kode' => 'VIA', 'nama' => 'VI Hasyim Asy\'ari', 'guru' => 'Muslim Al Huda, S.Pd.I.', 'email' => 'muslimhuda@sekolah.sch.id', 'nip' => '198302192008011026', 'jk' => 'L'],
            ['tingkat' => '6', 'kode' => 'VIB', 'nama' => 'VI Ahmad Dahlan', 'guru' => 'Sumining, S.Pd.', 'email' => 'sumining@sekolah.sch.id', 'nip' => '198001052005012027', 'jk' => 'P'],
            ['tingkat' => '6', 'kode' => 'VIC', 'nama' => 'VI Ahmad Siddiq', 'guru' => 'Khafidhoh, S.Pd.I.', 'email' => 'khafidhoh@sekolah.sch.id', 'nip' => '198711202011012028', 'jk' => 'P'],
        ];

        // Buat akun guru kelas dan rombel
        $createdKelas = [];
        $firstGuru = null;

        foreach ($rombelData as $idx => $r) {
            $guruUser = User::create([
                'name' => $r['guru'],
                'email' => $idx === 0 ? 'guru@sekolah.sch.id' : $r['email'], // guru@sekolah.sch.id untuk login default demo
                'password' => $defaultPassword,
                'role' => 'guru',
                'jenis_guru' => 'guru_kelas',
                'mata_pelajaran' => 'Guru Kelas',
                'nip' => $r['nip'],
                'phone' => '0812' . rand(10000000, 99999999),
                'gender' => $r['jk'],
            ]);

            if ($idx === 0) {
                $firstGuru = $guruUser;
            }

            $kelas = Kelas::create([
                'nama_kelas' => $r['nama'],
                'tingkat' => $r['tingkat'],
                'jurusan' => 'Madrasah Ibtidaiyah',
                'wali_kelas_id' => $guruUser->id,
                'tahun_ajaran' => '2026/2027',
            ]);

            $createdKelas[] = $kelas;
        }

        // 3. Guru Mata Pelajaran (Mapel) MIN 2 Jember
        $guruMapelData = [
            ['nama' => 'Saroji, S.Pd.I', 'mapel' => 'Aqidah Akhlak', 'email' => 'saroji@sekolah.sch.id', 'nip' => '198304122009011030', 'jk' => 'L'],
            ['nama' => 'Umi Kulsum, S.Pd.I.', 'mapel' => 'Aqidah Akhlak', 'email' => 'umikulsum@sekolah.sch.id', 'nip' => '198507202010012031', 'jk' => 'P'],
            ['nama' => 'Sya\'roni, S.Pd.I', 'mapel' => 'Al Qur\'an Hadis', 'email' => 'syaroni@sekolah.sch.id', 'nip' => '198109152007011032', 'jk' => 'L'],
            ['nama' => 'Askhiyah Sholihati, S.Pd.I.', 'mapel' => 'Al Qur\'an Hadis', 'email' => 'askhiyah@sekolah.sch.id', 'nip' => '198602182011012033', 'jk' => 'P'],
            ['nama' => 'Umi Khamlatin, S. Pd.I', 'mapel' => 'Fikih', 'email' => 'umikhamlatin@sekolah.sch.id', 'nip' => '198408102008012034', 'jk' => 'P'],
            ['nama' => 'Nidaul Huriyah, S.Pd.I.', 'mapel' => 'SKI', 'email' => 'nidaul@sekolah.sch.id', 'nip' => '199012242015032035', 'jk' => 'P'],
            ['nama' => 'Muhammad Luhmas Dievka Al Kindi, S. Pd.', 'mapel' => 'Bahasa Arab', 'email' => 'dieva.alkindi@sekolah.sch.id', 'nip' => '199305142019031036', 'jk' => 'L'],
            ['nama' => 'Izzatul Millah, S. Pd', 'mapel' => 'Bahasa Arab', 'email' => 'izzatul@sekolah.sch.id', 'nip' => '199203172018022037', 'jk' => 'P'],
            ['nama' => 'Mukhtarikin, S.Pd.', 'mapel' => 'Penjaskes', 'email' => 'mukhtarikin@sekolah.sch.id', 'nip' => '198006112005011038', 'jk' => 'L'],
            ['nama' => 'Cuncun Akbar, S.Pd.', 'mapel' => 'Penjaskes', 'email' => 'cuncun@sekolah.sch.id', 'nip' => '198901222014021039', 'jk' => 'L'],
            ['nama' => 'Fahriza Nur Aini, S.Hum.', 'mapel' => 'Bahasa Jawa', 'email' => 'fahriza@sekolah.sch.id', 'nip' => '199408292020122040', 'jk' => 'P'],
            ['nama' => 'Izza Maulidia Idris, S.Pd.', 'mapel' => 'Bahasa Inggris', 'email' => 'izzamaulidia@sekolah.sch.id', 'nip' => '199503112022032041', 'jk' => 'P'],
        ];

        foreach ($guruMapelData as $gm) {
            $userGm = User::create([
                'name' => $gm['nama'],
                'email' => $gm['email'],
                'password' => $defaultPassword,
                'role' => 'guru',
                'jenis_guru' => 'guru_mapel',
                'mata_pelajaran' => $gm['mapel'],
                'nip' => $gm['nip'],
                'phone' => '0813' . rand(10000000, 99999999),
                'gender' => $gm['jk'],
            ]);

            // Tugaskan ke kelas-kelas sesuai jenjang (misal 5 kelas pertama)
            foreach (array_slice($createdKelas, 0, 7) as $k) {
                $userGm->kelasAjar()->attach($k->id, ['mata_pelajaran' => $gm['mapel']]);
            }
        }

        // 4. Seed Siswa untuk Kelas IA Sunan Ampel (dan beberapa kelas lain)
        $namaSiswaMIN = [
            ['nama' => 'Achmad Faiz Nurrohman', 'jk' => 'L'],
            ['nama' => 'Aisyah Putri Azzahra', 'jk' => 'P'],
            ['nama' => 'Alfi Syahril Ramadhan', 'jk' => 'L'],
            ['nama' => 'Anindya Khansa Nafisah', 'jk' => 'P'],
            ['nama' => 'Bilal Habiburrahman', 'jk' => 'L'],
            ['nama' => 'Dhia Syarafana Qotrunnada', 'jk' => 'P'],
            ['nama' => 'Fathan Al Ghifari', 'jk' => 'L'],
            ['nama' => 'Hanifa Zahratussita', 'jk' => 'P'],
            ['nama' => 'Ibrahim Khalilurrahman', 'jk' => 'L'],
            ['nama' => 'Kayla Zhafira Nabila', 'jk' => 'P'],
            ['nama' => 'Muhammad Rayyan Al Farizi', 'jk' => 'L'],
            ['nama' => 'Nayla Khumaira', 'jk' => 'P'],
            ['nama' => 'Rasyid Hafizh Al Hakim', 'jk' => 'L'],
            ['nama' => 'Salma Aqila Mumtaza', 'jk' => 'P'],
            ['nama' => 'Zaidan Ahsanul Fikri', 'jk' => 'L'],
        ];

        $kelasIA = $createdKelas[0];
        $createdSiswaIA = [];
        foreach ($namaSiswaMIN as $i => $item) {
            $nis = '10' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $siswa = Siswa::create([
                'nis' => $nis,
                'nisn' => '0015' . str_pad($nis, 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelasIA->id,
                'no_absen' => $i + 1,
                'tempat_lahir' => 'Jember',
                'tanggal_lahir' => '2019-06-15',
                'alamat' => 'Jl. KH. Wahid Hasyim No. ' . ($i + 1) . ', Jember',
                'no_telepon_ortu' => '0821' . rand(10000000, 99999999),
                'status' => 'aktif',
            ]);
            $createdSiswaIA[] = $siswa;
        }

        // Siswa untuk Kelas IIA Ki Hajar Dewantara
        $kelasIIA = $createdKelas[7];
        foreach (array_slice($namaSiswaMIN, 0, 10) as $i => $item) {
            $nis = '20' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            Siswa::create([
                'nis' => $nis,
                'nisn' => '0014' . str_pad($nis, 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => $item['nama'],
                'jenis_kelamin' => $item['jk'],
                'kelas_id' => $kelasIIA->id,
                'no_absen' => $i + 1,
                'tempat_lahir' => 'Jember',
                'tanggal_lahir' => '2018-05-10',
                'alamat' => 'Jl. PB. Sudirman No. ' . ($i + 1) . ', Jember',
                'no_telepon_ortu' => '0822' . rand(10000000, 99999999),
                'status' => 'aktif',
            ]);
        }

        // 5. Seed Absensi 14 hari terakhir untuk Kelas IA Sunan Ampel
        $today = Carbon::today();
        for ($d = 20; $d >= 0; $d--) {
            $tgl = $today->copy()->subDays($d);
            if ($tgl->isWeekend()) {
                continue;
            }

            $dateStr = $tgl->format('Y-m-d');

            foreach ($createdSiswaIA as $idx => $s) {
                $status = 'hadir';
                $catatan = null;

                if ($idx === 3 && $d % 4 === 0) {
                    $status = 'sakit';
                    $catatan = 'Demam';
                } elseif ($idx === 6 && $d % 5 === 0) {
                    $status = 'izin';
                    $catatan = 'Acara keluarga';
                } elseif ($idx === 10 && $d === 2) {
                    $status = 'alpa';
                    $catatan = 'Tanpa keterangan';
                }

                Absensi::create([
                    'siswa_id' => $s->id,
                    'kelas_id' => $kelasIA->id,
                    'tanggal' => $dateStr,
                    'status' => $status,
                    'catatan' => $catatan,
                    'guru_id' => $firstGuru->id,
                ]);
            }
        }
    }
}
