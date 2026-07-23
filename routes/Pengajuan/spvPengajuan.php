<?php

use App\Http\Controllers\PengajuanApprovalController;
use App\Http\Controllers\PermintaanApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('spv')->name('spv.')->group(function () {

    // Pengajuan
    Route::get('/pengajuan', [PengajuanApprovalController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanApprovalController::class, 'show'])->name('pengajuan.show');
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanApprovalController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/reject', [PengajuanApprovalController::class, 'reject'])->name('pengajuan.reject');

    // Permintaan
    Route::get('/permintaan', [PermintaanApprovalController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/{permintaan}', [PermintaanApprovalController::class, 'show'])->name('permintaan.show');
    Route::post('/permintaan/{permintaan}/approve', [PermintaanApprovalController::class, 'approve'])->name('permintaan.approve');
    Route::post('/permintaan/{permintaan}/reject', [PermintaanApprovalController::class, 'reject'])->name('permintaan.reject');
});
