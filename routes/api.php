<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HrgaController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ========== Auth ==========
Route::post('/auth/login', [AuthController::class, 'apiLogin']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // ========== HRGA ==========
    Route::prefix('hrga')->group(function () {
        Route::get('/dashboard', [HrgaController::class, 'dashboard']);
        
        // Karyawan Management
        Route::prefix('karyawan')->group(function () {
            Route::get('/', [HrgaController::class, 'indexApi']);
            Route::post('/', [HrgaController::class, 'storeKaryawanApi']);
            Route::put('/{id}', [HrgaController::class, 'updateKaryawanApi']);
            Route::delete('/{id}', [HrgaController::class, 'destroyKaryawanApi']);
            Route::post('/{id}/send-email', [HrgaController::class, 'kirimEmailKaryawanApi']);
            Route::post('/rotate-shift', [HrgaController::class, 'rotateShiftApi']);
        });
        
        // Jadwal Pesanan
        Route::prefix('pesanan')->group(function () {
            Route::get('/', [HrgaController::class, 'showPesananApi']);
            Route::post('/', [HrgaController::class, 'storePesananApi']);
            Route::put('/{id}', [HrgaController::class, 'updatePesananApi']);
            Route::delete('/{id}', [HrgaController::class, 'destroyPesananApi']);
        });
        
        // Konsumsi
        Route::prefix('konsumsi')->group(function () {
            Route::get('/monitoring', [HrgaController::class, 'monitoringKonsumsiHarianApi']);
            Route::get('/report', [HrgaController::class, 'reportKonsumsiApi']);
            Route::get('/download', [HrgaController::class, 'downloadKonsumsiApi']);
        });
    });

    // ========== KOKI ==========
    Route::prefix('koki')->group(function () {
        Route::get('/pesanan', [KokiController::class, 'pesanan']);
        Route::post('/scan', [KokiController::class, 'processScan']);
        Route::get('/monitor/{idPesanan}', [KokiController::class, 'monitorScan']);
    });

    // ========== KARYAWAN ==========
    Route::prefix('karyawan')->group(function () {
        Route::get('/dashboard', [KaryawanController::class, 'dashboard']);
        Route::get('/qr', [KaryawanController::class, 'generateQrApi']);
    });
});