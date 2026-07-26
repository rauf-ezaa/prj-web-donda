
<?php

use App\Http\Controllers\StokOpnameSpvController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('spv')->name('spv.')->group(function () {

Route::get('stok-opname', [StokOpnameSpvController::class, 'index'])->name('stok-opname.index');
Route::get('stok-opname/{stok_opname}', [StokOpnameSpvController::class, 'show'])->name('stok-opname.show');
Route::post('stok-opname/{stok_opname}/verify', [StokOpnameSpvController::class, 'verify'])->name('stok-opname.verify');
Route::post('stok-opname/{stok_opname}/cancel', [StokOpnameSpvController::class, 'cancel'])->name('stok-opname.cancel');
});
