<?php

use App\Http\Controllers\KaryawanController;
use Illuminate\Support\Facades\Route;

// Route::get('/data-karyawan', function () {
//   return view('pages.admin.data-karyawan.index-karyawan', ['title' => 'List Data Karyawan']);
// })->name('karyawanIndex');

// Route::get('/data-karyawan/create', function () {
//   return view('pages.admin.data-karyawan.create-karyawan', ['title' => 'Create Data Karyawan']);
// })->name('karyawanCreate');

Route::resource('data-karyawan', KaryawanController::class);