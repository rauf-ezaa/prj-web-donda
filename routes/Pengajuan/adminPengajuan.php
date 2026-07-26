<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengajuanApprovalAdminController;

Route::middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

Route::get('/pengajuan', [PengajuanApprovalAdminController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanApprovalAdminController::class, 'show'])->name('pengajuan.show');
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanApprovalAdminController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/reject', [PengajuanApprovalAdminController::class, 'reject'])->name('pengajuan.reject');
});
