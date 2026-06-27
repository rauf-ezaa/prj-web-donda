<?php

use Illuminate\Support\Facades\Route;

Route::get('/permintaan', function () {
  return view('pages.admin.permintaan-barang.index-permintaan', ['title' => 'List Data Permintaan']);
})->name('permintaan');