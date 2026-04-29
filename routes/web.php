<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PetugasMiddleware;
use App\Http\Controllers\Auth\LoginController;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\PanggilanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;

// use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Guest routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Admin routes
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin/', [DashboardController::class, 'index'])->name('dashboard.index');
    // Display (layar antrian)
    Route::get('/display', [DisplayController::class, 'index'])->name('display.index');
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    // Route AJAX untuk refresh daftar antrian
    Route::get('/antrian/list', [DisplayController::class, 'list'])->name('antrian.list');
    Route::get('/panggilan/next', [PanggilanController::class, 'getNextWaiting'])->name('panggilan.next');
    //unit
    Route::get('/admin/layanan/unit', [LayananController::class, 'unit'])->name('layanan.unit');
    Route::post('/admin/layanan/unitcreate', [LayananController::class, 'unitcreate'])->name('layanan.unitcreate');
    Route::put('/admin/layanan/unitupdate/{id}', [LayananController::class, 'unitupdate'])->name('layanan.unitupdate');
    Route::delete('/admin/layanan/unitdelete/{id}', [LayananController::class, 'unitdestroy'])->name('layanan.unitdestroy');
    //loket
    Route::get('/admin/loket', [LayananController::class, 'loket'])->name('layanan.loket');
    Route::post('/admin/layanan/loketcreate', [LayananController::class, 'loketcreate'])->name('layanan.loketcreate');
    Route::put('/admin/layanan/loketupdate/{id}', [LayananController::class, 'loketupdate'])->name('layanan.loketupdate');
    Route::delete('/admin/layanan/loketdelete/{id}', [LayananController::class, 'loketdestroy'])->name('layanan.loketdestroy');

    //running text
    Route::get('/admin/layanan/running-text', [LayananController::class, 'runningtext'])->name('layanan.runningtext');
    Route::post('/admin/layanan/running-text', [LayananController::class, 'runningtextupdate'])->name('layanan.runningtextupdate');

    //Video
    Route::get('/admin/layanan/video', [LayananController::class, 'video'])->name('layanan.video');
    Route::post('/admin/layanan/video', [LayananController::class, 'videoupdate'])->name('layanan.videoupdate');

    //antrian
    Route::get('/admin/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::post('/admin/antrian/{unitId}', [AntrianController::class, 'resetUnit'])->name('antrian.resetunit');

    //Loket
    Route::get('/{loketId}/panggilan', [LoketController::class, 'panggilan'])->name('loket.panggilan');
    Route::post('/{loketId}/panggil', [LoketController::class, 'panggil'])->name('loket.panggil');
    Route::post('/{loketId}/ulang', [LoketController::class, 'ulang'])->name('loket.ulang');
    Route::post('/{loketId}/proses', [LoketController::class, 'proses'])->name('loket.proses');
    Route::post('/{loketId}/lewati', [LoketController::class, 'lewati'])->name('loket.lewati');
    Route::post('/{loketId}/panggil-ulang', [LoketController::class, 'panggilUlang'])->name('loket.panggil-ulang');



    // Manajemen User
    Route::get('/admin/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/admin/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/admin/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Tampilkan halaman ambil antrian
    Route::get('/ambil-antrian', [AntrianController::class, 'tampilAmbilAntrian'])->name('antrian.tampil');

    // Ambil antrian
    Route::post('/ambil-antrian', [AntrianController::class, 'ambil'])->name('antrian.ambil');
    Route::get('/selesai/{id}', [AntrianController::class, 'selesai'])->name('antrian.selesai');
    // Halaman siap cetak
    Route::get('/cetak/{id}', [AntrianController::class, 'cetak'])->name('antrian.cetak');

    //laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
