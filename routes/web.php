<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\HrgaController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin']);
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // HRGA
    Route::prefix('hrga')->group(function () {
        Route::get('/dashboard', [HrgaController::class, 'dashboard'])->name('hrga.dashboard');
        
        // Karyawan Management
        Route::prefix('karyawan')->group(function () {
            Route::get('/', [HrgaController::class, 'index'])->name('hrga.karyawan.index');
            Route::get('/create', [HrgaController::class, 'createKaryawan'])->name('hrga.karyawan.create');
            Route::post('/', [HrgaController::class, 'storeKaryawan'])->name('hrga.karyawan.store');
            Route::get('/{user}/edit', [HrgaController::class, 'editKaryawan'])->name('hrga.karyawan.edit');
            Route::put('/{user}', [HrgaController::class, 'updateKaryawan'])->name('hrga.karyawan.update');
            Route::delete('/{user}', [HrgaController::class, 'destroyKaryawan'])->name('hrga.karyawan.destroy');
            Route::post('/{id}/send-email', [HrgaController::class, 'kirimEmailKaryawan'])->name('hrga.karyawan.send-email');
            Route::post('/rotate-shift', [HrgaController::class, 'rotateShift'])->name('hrga.karyawan.rotate-shift');
        });
        
        // Jadwal Pesanan
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [HrgaController::class, 'showPesanan'])->name('hrga.pesanan.index');
            Route::get('/create', [HrgaController::class, 'createPesanan'])->name('hrga.pesanan.create');
            Route::post('/', [HrgaController::class, 'storePesanan'])->name('hrga.pesanan.store');
            Route::get('/{id}/edit', [HrgaController::class, 'editPesanan'])->name('hrga.pesanan.edit');
            Route::put('/{id}', [HrgaController::class, 'updatePesanan'])->name('hrga.pesanan.update');
            Route::delete('/{id}', [HrgaController::class, 'destroyPesanan'])->name('hrga.pesanan.destroy');
        });
        
        // Konsumsi
        Route::get('/konsumsi', [HrgaController::class, 'monitoringKonsumsiHarian'])->name('hrga.konsumsi');
        Route::get('/report', [HrgaController::class, 'reportKonsumsi'])->name('hrga.report');
        Route::get('/download-konsumsi', [HrgaController::class, 'downloadKonsumsi'])->name('hrga.download-konsumsi');
    });

    // Karyawan
    Route::prefix('karyawan')->group(function () {
        Route::get('/dashboard', [KaryawanController::class, 'dashboard'])->name('karyawan.dashboard');
        Route::post('/generate-qr', [KaryawanController::class, 'generateQr'])->name('karyawan.generateQr');
        Route::get('/generate-qr', [KaryawanController::class, 'generateQrAjax'])->name('karyawan.generateQrAjax');
    });

    // Koki
    Route::prefix('koki')->group(function () {
        Route::get('/dashboard', [KokiController::class, 'index'])->name('koki.dashboard');
        Route::get('/pesanan', [KokiController::class, 'pesanan'])->name('koki.pesanan');
        Route::get('/scanqr/{idPesanan}', [KokiController::class, 'scanQr'])->name('koki.scanQr');
        Route::post('/process-scan', [KokiController::class, 'processScan'])->name('koki.processScan');
        Route::get('/dashboard/scan/monitor/{idPesanan}', [KokiController::class, 'monitorScan'])->name('koki.monitorScan');
    });

});