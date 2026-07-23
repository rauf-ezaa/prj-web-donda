<?php
use App\Http\Controllers\PermintaanApprovalAdminController;
use App\Http\Controllers\PermintaanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('admin')->name('admin.')->group(function () {
		Route::get('/permintaan', [PermintaanApprovalAdminController::class, 'index'])->name('permintaan.index');
    Route::get('/permintaan/{permintaan}', [PermintaanApprovalAdminController::class, 'show'])->name('permintaan.show');
    Route::post('/permintaan/{permintaan}/approve', [PermintaanApprovalAdminController::class, 'approve'])->name('permintaan.approve');
    Route::post('/permintaan/{permintaan}/reject', [PermintaanApprovalAdminController::class, 'reject'])->name('permintaan.reject');
});
