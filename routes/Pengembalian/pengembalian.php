<?php

use Illuminate\Support\Facades\Route;

Route::get('/pengembalian', function () {
  return view('pages.admin.peminjaman-pengembalian.index-pengembalian', ['title' => 'List Data Pengembalian']);
})->name('pengembalian');