# SIABSEN &mdash; Sistem Informasi Presensi Siswa (MIN 2 Jember)

Aplikasi Web Sistem Informasi Absensi / Presensi Siswa berbasis Laravel 12 & Tailwind CSS yang dirancang modern, responsif, dan mudah digunakan untuk manajemen presensi sekolah.

---

## ✨ Fitur Utama

### 1. Peran Tata Usaha (TU / Administrator)
- **Dashboard Monitoring**:
  - Ringkasan kehadiran harian (Hadir, Izin, Sakit, Alpa).
  - Filter interaktif per rombel kelas & seluruh sekolah.
  - Grafik tren kehadiran harian interaktif (7 hari, 14 hari, 30 hari, bulan ini).
  - Pemantauan status pengisian absensi kelas secara real-time.
- **Kelola Kelas (Rombel)**: Tambah, edit, detail kelas, penugasan wali kelas, dan proteksi integritas data.
- **Kelola Siswa**: Manajemen data induk siswa terdaftar, NIS, nomor absen, penempatan rombel, dan status keaktifan.
- **Kelola Guru**: Manajemen akun tenaga pendidik, NIP, email, dan hak akses kelas.
- **Rekapitulasi Harian & Bulanan**: Matriks laporan kehadiran siswa lengkap per tanggal dan per bulan.

### 2. Peran Guru / Wali Kelas
- **Dashboard Guru**: Status pengisian presensi kelas yang diampu, ringkasan metrik kehadiran, grafik tren rombel, dan akumulasi presensi siswa bulanan.
- **Input Presensi Siswa**: Antarmuka cepat untuk mencatat presensi harian siswa (Hadir, Izin, Sakit, Alpa, beserta catatan keterangan).
- **Rekap Presensi Bulanan**: Rekapitulasi presensi bulanan lengkap siswa di kelas yang diampu.

---

## 🛠️ Stack Teknologi

- **Backend**: PHP 8.2+ / Laravel 12
- **Database**: MySQL / MariaDB (Relational schema dengan foreign key & cascade protection)
- **Frontend**: Blade Templating, Tailwind CSS, Google Font (Plus Jakarta Sans)
- **Charts**: Interactive Custom CSS / Tailwind Visual Bar Chart

---

## 🚀 Panduan Instalasi Lokal

1. **Clone Repository**:
   ```bash
   git clone https://github.com/Mafirrr/absensi-MIN2Jember.git
   cd absensi-MIN2Jember
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   Salin berkas `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database MySQL Anda di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_absensi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Buka browser di `http://127.0.0.1:8000`.

---

## 🔐 Akun Default (Hasil Seeder)

| Peran | Email | Password |
|---|---|---|
| **Tata Usaha** | `tu@siabsen.sch.id` | `password` |
| **Guru / Wali Kelas** | `guru@siabsen.sch.id` | `password` |

---

## 🧪 Pengujian Otomatis

Aplikasi ini dilengkapi pengujian fitur dan unit (*Feature & Unit Tests*) lengkap:
```bash
php artisan test
```

---

&copy; 2026 SIABSEN &bull; Sistem Informasi Presensi Siswa.
