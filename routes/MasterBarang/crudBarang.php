<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

// Route::get('/data-barang', function () {
//   return view('pages.admin.data-barang.index', ['title' => 'List Data Barang']);
// })->name('barangIndex');

// Route::get('/data-barang/create', function () {
//   return view('pages.admin.data-barang.create-barang', ['title' => 'Create Data Barang']);
// })->name('barangCreate');

Route::resource('data-barang', BarangController::class)
  ->parameters(['data-barang' => 'barang']);



// Route::get('/kartu-inventaris-barang', function () {
//   return view('pages.admin.kartu-inventaris-barang.index-inventaris', ['title' => 'List Data KIB']);
// })->name('kibIndex');

// Route::get('/kartu-inventaris-barang/create', function () {
//   return view('pages.admin.kartu-inventaris-barang.create-inventaris', ['title' => 'Create Data KIB']);
// })->name('createKib');
