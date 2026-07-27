<?php

use App\Http\Controllers\PembelianAdminController;
use Illuminate\Support\Facades\Route;

    Route::get('pembelian', [PembelianAdminController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianAdminController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian', [PembelianAdminController::class, 'store'])->name('pembelian.store');
    Route::get('pembelian/{pembelian}', [PembelianAdminController::class, 'show'])->name('pembelian.show');
		  Route::get('pembelian/{pembelian}/edit', [PembelianAdminController::class, 'edit'])->name('pembelian.edit');
    Route::put('pembelian/{pembelian}', [PembelianAdminController::class, 'update'])->name('pembelian.update');
    Route::get('pembelian/{pembelian}', [PembelianAdminController::class, 'show'])->name('pembelian.show');
