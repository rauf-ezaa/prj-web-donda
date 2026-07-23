<?php

use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan/draft/start', [PengajuanController::class, 'startDraft'])->name('pengajuan.draft.start');
    Route::get('/pengajuan/draft/{pengajuan}', [PengajuanController::class, 'showDraft'])->name('pengajuan.draft');
    Route::post('/pengajuan/{pengajuan}/items', [PengajuanController::class, 'addItem'])->name('pengajuan.items.add');
    Route::delete('/pengajuan/{pengajuan}/items/{detail}', [PengajuanController::class, 'removeItem'])->name('pengajuan.items.remove');
    Route::post('/pengajuan/{pengajuan}/verifikasi', [PengajuanController::class, 'verifikasi'])->name('pengajuan.verifikasi');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
		Route::post('/pengajuan/{pengajuan}/batalkan', [PengajuanController::class, 'batalkan'])->name('pengajuan.batalkan');
});
