<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| ROUTE HALAMAN UTAMA
|--------------------------------------------------------------------------
*/
// Arahkan ke dashboard jika sudah login, ke login jika belum
Route::get('/', [GuestController::class, 'index'])->name('welcome');


/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Hanya untuk yang belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Wajib Login - Akses Umum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data (Kecuali Hapus - Akses hapus hanya untuk Admin)
    Route::resource('buku', BukuController::class)->except(['destroy']);
    Route::resource('kategori', KategoriController::class)->except(['destroy']);
    Route::resource('anggota', AnggotaController::class)->except(['destroy']);

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::put('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'exportCsv'])->name('laporan.export');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Wajib Login & Harus Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Aksi Hapus (Destroy) dipindahkan ke sini
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::delete('/anggota/{anggota}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    // Pengaturan Aplikasi
    Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
});