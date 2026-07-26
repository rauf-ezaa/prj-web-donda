<?php

use App\Http\Controllers\PeriodeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:spv'])->prefix('spv')->name('spv.')->group(function () {
	Route::get('periode', [PeriodeController::class, 'index'])->name('periode.index');
	Route::get('periode/create', [PeriodeController::class, 'create'])->name('periode.create');
	Route::post('periode', [PeriodeController::class, 'store'])->name('periode.store');
	Route::post('periode/{periode}/kunci', [PeriodeController::class, 'kunci'])->name('periode.kunci');
	Route::post('periode/{periode}/buka-kunci', [PeriodeController::class, 'bukaKunci'])->name('periode.buka-kunci');
});
