<?php

use App\Http\Controllers\PembelianSpvController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('spv')->name('spv.')->group(function () {
 Route::get('pembelian', [PembelianSpvController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/{pembelian}', [PembelianSpvController::class, 'show'])->name('pembelian.show');
    Route::post('pembelian/{pembelian}/verify', [PembelianSpvController::class, 'verify'])->name('pembelian.verify');
    Route::post('pembelian/{pembelian}/reject', [PembelianSpvController::class, 'reject'])->name('pembelian.reject');
});
