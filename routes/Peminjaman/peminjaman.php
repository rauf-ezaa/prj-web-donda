<?php

use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

	// sisi user pengembalian
	Route::get('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'showKembalikan'])->name('peminjaman.kembalikan');
	Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'prosesKembalikan'])->name('peminjaman.kembalikan.proses');

	// sisi USER (peminjam)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/draft/start', [PeminjamanController::class, 'startDraft'])->name('peminjaman.draft.start');
    Route::get('/peminjaman/draft/{peminjaman}', [PeminjamanController::class, 'showDraft'])->name('peminjaman.draft');
    Route::post('/peminjaman/{peminjaman}/items', [PeminjamanController::class, 'addItem'])->name('peminjaman.items.add');
    Route::delete('/peminjaman/{peminjaman}/items/{detail}', [PeminjamanController::class, 'removeItem'])->name('peminjaman.items.remove');
    Route::post('/peminjaman/{peminjaman}/verifikasi', [PeminjamanController::class, 'verifikasi'])->name('peminjaman.verifikasi');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/ajukan-kembali', [PeminjamanController::class, 'ajukanKembali'])->name('peminjaman.ajukan-kembali');
		Route::post('/peminjaman/{peminjaman}/batalkan', [PeminjamanController::class, 'batalkan'])->name('peminjaman.batalkan');

    // sisi SPV

});
