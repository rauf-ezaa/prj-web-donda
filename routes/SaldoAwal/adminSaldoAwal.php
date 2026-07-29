<?php

use App\Http\Controllers\SaldoAwalAdminController;
use Illuminate\Support\Facades\Route;

		Route::get('saldo-awal', [SaldoAwalAdminController::class, 'index'])->name('saldo-awal.index');
    Route::get('saldo-awal/create', [SaldoAwalAdminController::class, 'create'])->name('saldo-awal.create');
    Route::post('saldo-awal', [SaldoAwalAdminController::class, 'store'])->name('saldo-awal.store');
    Route::get('saldo-awal/{saldo_awal}/edit', [SaldoAwalAdminController::class, 'edit'])->name('saldo-awal.edit');
		 Route::put('saldo-awal/{saldo_awal}', [SaldoAwalAdminController::class, 'update'])->name('saldo-awal.update');
		Route::get('saldo-awal/{saldo_awal}', [SaldoAwalAdminController::class, 'show'])->name('saldo-awal.show');
		Route::get('saldo-awal/rekap', [SaldoAwalAdminController::class, 'rekap'])->name('saldo-awal.rekap');
		Route::get('saldo-awal/rekap/{barangId}', [SaldoAwalAdminController::class, 'rincian'])->name('saldo-awal.rincian');
