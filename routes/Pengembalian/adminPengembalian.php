<?php
use App\Http\Controllers\PengembalianAdminController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')->group(function () {
    Route::get('pengembalian', [PengembalianAdminController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{pengembalian}', [PengembalianAdminController::class, 'show'])->name('pengembalian.show');
    Route::post('pengembalian/{pengembalian}/verify', [PengembalianAdminController::class, 'verify'])->name('pengembalian.verify');
    Route::post('pengembalian/{pengembalian}/reject', [PengembalianAdminController::class, 'reject'])->name('pengembalian.reject');
});
