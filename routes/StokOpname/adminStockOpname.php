<?php

use App\Http\Controllers\SaldoAwalAdminController;
use App\Http\Controllers\StokOpnameAdminController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
		Route::get('stok-opname/{stok_opname}/cetak-bast', [StokOpnameAdminController::class, 'cetakBast'])->name('stok-opname.cetak-bast');
    Route::get('stok-opname', [StokOpnameAdminController::class, 'index'])->name('stok-opname.index');
		Route::get('stok-opname/create', [StokOpnameAdminController::class, 'create'])->name('stok-opname.create');
    Route::post('stok-opname/start', [StokOpnameAdminController::class, 'start'])->name('stok-opname.start');
    Route::get('stok-opname/{stok_opname}/edit', [StokOpnameAdminController::class, 'edit'])->name('stok-opname.edit');
    Route::post('stok-opname/{stok_opname}/submit', [StokOpnameAdminController::class, 'submit'])->name('stok-opname.submit');
    Route::get('stok-opname/{stok_opname}', [StokOpnameAdminController::class, 'show'])->name('stok-opname.show');
    Route::get('saldo-awal/create-from-periode', [SaldoAwalAdminController::class, 'createFromPeriode'])->name('saldo-awal.create-from-periode');
});
