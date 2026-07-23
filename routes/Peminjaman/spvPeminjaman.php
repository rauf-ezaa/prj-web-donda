 <?php

use App\Http\Controllers\PeminjamanApprovalController;
use Illuminate\Support\Facades\Route;

 Route::middleware(['auth', 'role:admin'])->prefix('spv')->name('spv.')->group(function () {
        Route::get('/peminjaman', [PeminjamanApprovalController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanApprovalController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanApprovalController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanApprovalController::class, 'reject'])->name('peminjaman.reject');
        Route::post('/peminjaman/{peminjaman}/konfirmasi-kembali', [PeminjamanApprovalController::class, 'konfirmasiKembali'])->name('peminjaman.konfirmasi-kembali');
    });
