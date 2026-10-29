<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\RekapController as GuruRekapController;
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

    // Kelola Kelas
    Route::resource('kelas', TuKelasController::class);

    // Kelola Siswa
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

    // Rekap Bulanan Kelas yang Diajar
    Route::get('/rekap/bulanan', [GuruRekapController::class, 'bulanan'])->name('rekap.bulanan');
});
