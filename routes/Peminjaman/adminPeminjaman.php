<?php

use App\Http\Controllers\PeminjamanApprovalAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('admin')->name('admin.')->group(function () {

Route::get('/peminjaman', [PeminjamanApprovalAdminController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanApprovalAdminController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanApprovalAdminController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanApprovalAdminController::class, 'reject'])->name('peminjaman.reject');
});
