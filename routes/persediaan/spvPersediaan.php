<?php

use App\Http\Controllers\PersediaanApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('spv')->name('spv.')->group(function () {
    Route::get('/persediaan', [PersediaanApprovalController::class, 'index'])->name('persediaan.index');
    Route::get('/persediaan/{persedian}', [PersediaanApprovalController::class, 'show'])->name('persediaan.show');
    Route::post('/persediaan/{persedian}/approve', [PersediaanApprovalController::class, 'approve'])->name('persediaan.approve');
    Route::post('/persediaan/{persedian}/reject', [PersediaanApprovalController::class, 'reject'])->name('persediaan.reject');
});
