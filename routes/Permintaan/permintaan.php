<?php

use App\Http\Controllers\PermintaanController;
use Illuminate\Support\Facades\Route;

Route::get('/permintaan/draft/{permintaan}', [PermintaanController::class, 'showDraft'])->name('permintaan.draft');
Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan.index');
Route::post('/permintaan/draft/start', [PermintaanController::class, 'startDraft'])->name('permintaan.draft.start');
Route::delete('/permintaan/{permintaan}/items/{detail}', [PermintaanController::class, 'removeItem'])->name('permintaan.items.remove');
Route::get('/permintaan/{permintaan}', [PermintaanController::class, 'show'])->name('permintaan.show');
Route::post('/permintaan/{permintaan}/batalkan', [PermintaanController::class, 'batalkan'])->name('permintaan.batalkan');
