<?php

use App\Http\Controllers\PengembalianVerifikasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('spv')->name('spv.')->group(function () {
    Route::get('pengembalian', [PengembalianVerifikasiController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{pengembalian}', [PengembalianVerifikasiController::class, 'show'])->name('pengembalian.show');
    Route::post('pengembalian/{pengembalian}/verify', [PengembalianVerifikasiController::class, 'verify'])->name('pengembalian.verify');
    Route::post('pengembalian/{pengembalian}/reject', [PengembalianVerifikasiController::class, 'reject'])->name('pengembalian.reject');
});
