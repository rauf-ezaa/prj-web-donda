<?php

use App\Http\Controllers\SaldoAwalSpvController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('spv')->name('spv.')->group(function () {

		Route::get('saldo-awal', [SaldoAwalSpvController::class, 'index'])->name('saldo-awal.index');
    Route::get('saldo-awal/{saldo_awal}', [SaldoAwalSpvController::class, 'show'])->name('saldo-awal.show');
    Route::post('saldo-awal/{saldo_awal}/verify', [SaldoAwalSpvController::class, 'verify'])->name('saldo-awal.verify');
    Route::post('saldo-awal/{saldo_awal}/reject', [SaldoAwalSpvController::class, 'reject'])->name('saldo-awal.reject');
});
