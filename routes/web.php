<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Panel;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PpdbController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PPDB ONLINE (PROTOTYPE TANPA DATABASE)
|--------------------------------------------------------------------------
| Roles: admin, operator (panitia seleksi), pendaftar.
| Data dummy: config/ppdb.php — perubahan disimpan di session (App\Support\Ppdb).
*/

/* Publik */
Route::get('/', [PpdbController::class, 'home'])->name('home');
Route::get('/pengumuman', [PpdbController::class, 'pengumuman'])->name('pengumuman');

/* Pendaftaran & autentikasi (tamu) */
Route::middleware('ppdb.guest')->group(function () {
    Route::get('/daftar', [PendaftaranController::class, 'create'])->name('daftar');
    Route::post('/daftar', [PendaftaranController::class, 'store'])->middleware('throttle:10,1')->name('daftar.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
});

Route::get('/daftar/berhasil', [PendaftaranController::class, 'sukses'])->name('daftar.sukses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* Pendaftar */
Route::prefix('akun')->name('pendaftar.')->middleware('ppdb.role:pendaftar')->group(function () {
    Route::get('/', [PendaftarController::class, 'dashboard'])->name('dashboard');
    Route::get('/biodata', [PendaftarController::class, 'biodata'])->name('biodata');
    Route::get('/berkas', [PendaftarController::class, 'berkas'])->name('berkas');
    Route::post('/berkas', [PendaftarController::class, 'unggah'])->name('berkas.unggah');
    Route::get('/kartu', [PendaftarController::class, 'kartu'])->name('kartu');
});

/* Panel admin & operator (panitia seleksi) */
Route::prefix('panel')->name('panel.')->middleware('ppdb.role:admin,operator')->group(function () {
    Route::get('/', [Panel\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pendaftar', [Panel\PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/{no}', [Panel\PendaftarController::class, 'show'])->name('pendaftar.show');
    Route::post('/pendaftar/{no}/verifikasi', [Panel\PendaftarController::class, 'verifikasi'])->name('pendaftar.verifikasi');

    Route::get('/seleksi', [Panel\SeleksiController::class, 'index'])->name('seleksi');
    Route::post('/seleksi/{no}', [Panel\SeleksiController::class, 'putuskan'])->name('seleksi.putuskan');

    // Khusus admin
    Route::middleware('ppdb.role:admin')->group(function () {
        Route::post('/seleksi-umumkan', [Panel\SeleksiController::class, 'umumkan'])->name('seleksi.umumkan');

        Route::get('/pengguna', [Panel\PenggunaController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [Panel\PenggunaController::class, 'store'])->name('pengguna.store');
        Route::post('/pengguna/status', [Panel\PenggunaController::class, 'toggle'])->name('pengguna.toggle');

        Route::get('/pengaturan', [Panel\PengaturanController::class, 'edit'])->name('pengaturan.edit');
        Route::put('/pengaturan', [Panel\PengaturanController::class, 'update'])->name('pengaturan.update');
    });
});
