<?php

use Illuminate\Support\Facades\Route;

Route::get('/pengajuan', function () {
  return view('pages.admin.pengajuan-barang.index-pengajuan', ['title' => 'List Data Pengajuan']);
})->name('pengajuan');