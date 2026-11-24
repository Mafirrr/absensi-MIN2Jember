<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\RekapController as GuruRekapController;
use App\Http\Controllers\Guru\SiswaController as GuruSiswaController;
use App\Http\Controllers\Tu\DashboardController as TuDashboardController;
use App\Http\Controllers\Tu\GuruController as TuGuruController;
use App\Http\Controllers\Tu\KelasController as TuKelasController;
use App\Http\Controllers\Tu\RekapController as TuRekapController;
use App\Http\Controllers\Tu\SiswaController as TuSiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirection
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'tu') {
            return redirect()->route('tu.dashboard');
        }
        return redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Tata Usaha (TU) Routes - Akses Semua Kelas & Manajemen Data
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tu'])->prefix('tu')->as('tu.')->group(function () {
    Route::get('/dashboard', [TuDashboardController::class, 'index'])->name('dashboard');

    // Rekap Absensi
    Route::get('/rekap/harian', [TuRekapController::class, 'harian'])->name('rekap.harian');
    Route::get('/rekap/bulanan', [TuRekapController::class, 'bulanan'])->name('rekap.bulanan');
    Route::get('/rekap/bulanan/export-excel', [TuRekapController::class, 'exportBulananExcel'])->name('rekap.bulanan.excel');
    Route::get('/rekap/bulanan/export-pdf', [TuRekapController::class, 'exportBulananPdf'])->name('rekap.bulanan.pdf');
    Route::get('/rekap/semester', [TuRekapController::class, 'semester'])->name('rekap.semester');
    Route::get('/rekap/semester/export-excel', [TuRekapController::class, 'exportSemesterExcel'])->name('rekap.semester.excel');
    Route::get('/rekap/semester/export-pdf', [TuRekapController::class, 'exportSemesterPdf'])->name('rekap.semester.pdf');

    // Kelola Kelas
    Route::resource('kelas', TuKelasController::class);

    // Kelola Siswa
    Route::get('/siswa/template', [TuSiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::post('/siswa/bulk', [TuSiswaController::class, 'bulkStore'])->name('siswa.bulk');
    Route::resource('siswa', TuSiswaController::class);

    // Kelola Guru & Tenaga Pendidik
    Route::resource('guru', TuGuruController::class);
});

/*
|--------------------------------------------------------------------------
| Guru / Wali Kelas Routes - Hanya Kelas yang Diajar
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])->prefix('guru')->as('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Input & Kelola Absensi
    Route::get('/absensi', [GuruAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [GuruAbsensiController::class, 'store'])->name('absensi.store');

    // Kelola Data Siswa di Rombel Guru (Siswa otomatis tersinkron ke TU)
    Route::get('/siswa', [GuruSiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [GuruSiswaController::class, 'store'])->name('siswa.store');
    Route::post('/siswa/bulk', [GuruSiswaController::class, 'bulkStore'])->name('siswa.bulk');
    Route::get('/siswa/template', [GuruSiswaController::class, 'downloadTemplate'])->name('siswa.template');

    // Rekap Absensi Kelas yang Diajar
    Route::get('/rekap/bulanan', [GuruRekapController::class, 'bulanan'])->name('rekap.bulanan');
    Route::get('/rekap/bulanan/export-excel', [GuruRekapController::class, 'exportBulananExcel'])->name('rekap.bulanan.excel');
    Route::get('/rekap/bulanan/export-pdf', [GuruRekapController::class, 'exportBulananPdf'])->name('rekap.bulanan.pdf');
    Route::get('/rekap/semester', [GuruRekapController::class, 'semester'])->name('rekap.semester');
    Route::get('/rekap/semester/export-excel', [GuruRekapController::class, 'exportSemesterExcel'])->name('rekap.semester.excel');
    Route::get('/rekap/semester/export-pdf', [GuruRekapController::class, 'exportSemesterPdf'])->name('rekap.semester.pdf');
});
