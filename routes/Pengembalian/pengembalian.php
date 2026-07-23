<?php
use App\Http\Controllers\PengembalianStaffController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Menu sendiri: daftar peminjaman yang bisa/sudah diproses pengembalian
    Route::get('pengembalian', [PengembalianStaffController::class, 'index'])->name('pengembalian.index');

    // Form input pengembalian untuk 1 peminjaman spesifik
    Route::get('pengembalian/{peminjaman}/create', [PengembalianStaffController::class, 'create'])->name('pengembalian.create');
    Route::post('pengembalian/{peminjaman}', [PengembalianStaffController::class, 'store'])->name('pengembalian.store');

    // Riwayat pengembalian yang sudah diajukan staff (opsional tapi berguna)
    Route::get('pengembalian/riwayat', [PengembalianStaffController::class, 'riwayat'])->name('pengembalian.riwayat');
    Route::get('pengembalian/riwayat/{pengembalian}', [PengembalianStaffController::class, 'riwayatShow'])->name('pengembalian.riwayat.show');
});
